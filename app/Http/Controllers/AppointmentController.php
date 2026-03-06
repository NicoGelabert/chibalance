<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Product;
use App\Models\Availability;
use App\Models\BlockedDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use App\Mail\AppointmentConfirmation;
use App\Mail\AdminNewAppointment;
use App\Mail\AppointmentCanceledUser;
use App\Mail\AppointmentCanceledAdmin;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
// Para añadir eventos a Google Calendar
use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;
use App\Models\GoogleCalendarToken;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        // Opcional: filtrar por rango (start/end) para que FullCalendar pida solo eventos visibles
        if ($request->has('start') && $request->has('end')) {
            return Appointment::with('product')
                ->whereBetween('date', [$request->start, $request->end])
                ->get();
        }
        return Appointment::with('product')->get();
    }

    public function store(Request $request)
    {
        // 1️⃣ Validación
        $validator = Validator::make($request->all(), [
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'email'          => 'required|email|max:150',
            'contact_number' => 'nullable|string|max:30',
            'product_id'     => 'required|exists:products,id',
            'date'           => 'required|date',
            'start_time'     => 'required',
            'notes'          => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        // 2️⃣ Obtener producto y duración
        $product = Product::findOrFail($validated['product_id']);
        $duration = (int) $product->duration;
        $duration = $duration > 0 ? $duration : 60;

        // 3️⃣ Parsear fecha y hora
        $start = Carbon::parse($validated['date'].' '.$validated['start_time']);
        $end   = $start->copy()->addMinutes($duration);

        // 4️⃣ Crear cancel_token único
        do {
            $cancelToken = Str::random(80);
        } while (Appointment::where('cancel_token', $cancelToken)->exists());

        // 5️⃣ Crear cita
        $appointment = Appointment::create([
            'first_name'     => $validated['first_name'],
            'last_name'      => $validated['last_name'],
            'email'          => $validated['email'],
            'contact_number'   => $validated['contact_number'] ?? null,
            'product_id'     => $product->id,
            'date'           => $start->format('Y-m-d'),
            'start_time'     => $start->format('H:i'),
            'end_time'       => $end->format('H:i'),
            'status'         => 'pending',
            'notes'          => $validated['notes'] ?? null,
            'cancel_token'   => $cancelToken,
        ]);

        // 6️⃣ Agregar evento al Google Calendar
        $this->addEventToGoogleCalendar($appointment);

        // 7️⃣ Enviar mails
        Mail::to($appointment->email)->send(new AppointmentConfirmation($appointment));
        Mail::to(config('mail.from.address'))->send(new AdminNewAppointment($appointment));

        return response()->json($appointment, 201);
    }

    public function cancel($token)
    {
        $appointment = Appointment::where('cancel_token', $token)->first();

        if (!$appointment) {
            return response('Reserva no encontrada o ya cancelada.', 404);
        }

        if ($appointment->status !== 'cancelled') {
            $appointment->status = 'cancelled';
            $appointment->save();

            Mail::to($appointment->email)->send(new AppointmentCanceledUser($appointment));
            Mail::to(config('mail.from.address'))->send(new AppointmentCanceledAdmin($appointment));
        }

        $products = Product::all();

        return view('appointments.cancelled_success', compact('products')); // O redirección con mensaje
    }

    public function show(Appointment $appointment)
    {
        return $appointment->load('product');
    }

    public function update(Request $request, Appointment $appointment)
    {
        // Similar a store: validar y comprobar solapamientos si cambia fecha/hora
        $appointment->update($request->all());
        return response()->json($appointment);
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return response()->noContent();
    }
    
    /**
     * Crear evento en Google Calendar
     */
    function addEventToGoogleCalendar($appointment)
    {
        // 1️⃣ Obtener token de la masajista
        $tokenData = GoogleCalendarToken::first();
        if (!$tokenData) return;

        $client = new Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setAccessType('offline');
        $client->setPrompt('consent');

        // Refrescar token si hace falta
        $client->setAccessToken([
            'access_token' => $tokenData->access_token,
            'refresh_token' => $tokenData->refresh_token,
            'expires_in' => Carbon::parse($tokenData->expires_at)->diffInSeconds(now()),
        ]);

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($tokenData->refresh_token);
            $newToken = $client->getAccessToken();
            $tokenData->update([
                'access_token' => $newToken['access_token'],
                'expires_at' => Carbon::now()->addSeconds($newToken['expires_in'] ?? 3600),
            ]);
        }

        $service = new Calendar($client);

        // 2️⃣ Fechas de inicio y fin
        $start = Carbon::parse($appointment->date.' '.$appointment->start_time)->toIso8601String();
        $end   = Carbon::parse($appointment->date.' '.$appointment->end_time)->toIso8601String();

        // 3️⃣ Crear evento único en el calendario de la masajista
        $event = new Event([
            'summary' => "{$appointment->product->title} appointment", // título visible en el calendario de ambos
            'description' => "Cliente: {$appointment->first_name} {$appointment->last_name}\n".
                            "Email: {$appointment->email}\n".
                            "Tel: {$appointment->contact_number}\n".
                            "Servicio: {$appointment->product->title}\n".
                            "Notas: {$appointment->notes}\n".
                            "Cancelar cita: " . url('/cancel-reservation/'.$appointment->cancel_token),
            'start' => new EventDateTime([
                'dateTime' => $start,
                'timeZone' => 'Europe/Madrid',
            ]),
            'end' => new EventDateTime([
                'dateTime' => $end,
                'timeZone' => 'Europe/Madrid',
            ]),
            'attendees' => [
                ['email' => $appointment->email], // cliente recibe invitación
            ],
            'reminders' => [
                'useDefault' => false,
                'overrides' => [
                    ['method' => 'email', 'minutes' => 60],
                    ['method' => 'popup', 'minutes' => 30],
                ],
            ],
        ]);

        try {
            $service->events->insert('primary', $event);
        } catch (\Exception $e) {
            \Log::error('Error creando evento en Google Calendar: '.$e->getMessage());
        }

    }

}
