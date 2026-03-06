<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentRequest;
use App\Http\Resources\AppointmentListResource;
use App\Http\Resources\AppointmentResource;
use App\Models\Api\Appointment;
use App\Models\Api\Availability;
use App\Models\Api\BlockedDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $start = $request->query('start') ?? now()->startOfMonth()->toDateString();
        $end   = $request->query('end') ?? now()->endOfMonth()->toDateString();

        // --- 1️⃣ Citas activas
        $appointments = Appointment::whereBetween('date', [$start, $end])
            ->whereRaw("TRIM(LOWER(status)) != 'cancelled'")
            ->get()
            ->map(function($a) {
                return [
                    'id' => $a->id,
                    'first_name' => $a->first_name,
                    'last_name' => $a->last_name,
                    'email' => $a->email,
                    'contact_number' => $a->contact_number,
                    'date' => $a->date,
                    'start_time' => $a->start_time,
                    'end_time' => $a->end_time,
                    'status' => $a->status,
                    'notes' => $a->notes,
                    'product_id' => $a->product_id,
                ];
            });

        $slots = [];

        // --- 2️⃣ Slots automáticos según Availability
        $period = \Carbon\CarbonPeriod::create($start, $end);
        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');

            // Omitir días bloqueados
            if (\App\Models\BlockedDate::where('date', $dateStr)->exists()) continue;

            $dayOfWeek = $date->dayOfWeek;
            $availabilities = \App\Models\Availability::where('day_of_week', $dayOfWeek)
                ->where('is_active', true)
                ->get();

            foreach ($availabilities as $rule) {
                // Solo si no hay cita ocupando el horario
                $exists = Appointment::where('date', $dateStr)
                    ->where('start_time', $rule->start_time)
                    ->whereRaw("TRIM(LOWER(status)) != 'cancelled'")
                    ->exists();

                if (!$exists) {
                    $slots[] = [
                        'date' => $dateStr,
                        'start_time' => $rule->start_time,
                        'end_time' => $rule->end_time
                    ];
                }
            }
        }

        // --- 3️⃣ Slots puntuales
        $customSlots = \App\Models\Timeslot::whereBetween('date', [$start, $end])
            ->where('is_active', true)
            ->get();

        foreach ($customSlots as $s) {
            $slots[] = [
                'date' => $s->date,
                'start_time' => $s->start_time,
                'end_time' => $s->end_time
            ];
        }

        return response()->json([
            'appointments' => $appointments,
            'slots' => $slots
        ]);
    }


    private function colorByStatus($status)
    {
        return match($status) {
            'cancelled' => '#9ca3af',
            'pending'   => '#f59e0b',
            'confirmed' => '#3b82f6',
            default     => '#6366f1',
        };
    }
    // public function index()
    // {
    //     $perPage = request('per_page', 10);
    //     $search = request('search', '');
    //     $sortField = request('sort_field', 'created_at');
    //     $sortDirection = request('sort_direction', 'desc');


    //     $query = Appointment::query()
    //         ->with('product')
    //         ->where('date', 'like', "%{$search}%")
    //         ->orderBy($sortField, $sortDirection)
    //         ->paginate($perPage);


    //     return AppointmentListResource::collection($query);
    // }

    /**
     * Store a newly created resource in storage.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */


    public function store(AppointmentRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        $appointment = Appointment::create($data);


        return new AppointmentResource($appointment);
    }

    /**
     * Display the specified resource.
     * @param \App\Models\Appointment $appointment
     * @return \Illuminate\Http\Response
     */
    public function show(Appointment $appointment)
    {
        return new AppointmentResource($appointment);
    }

    public function update(AppointmentRequest $request, Appointment $appointment)
    {
        $data = $request->validated();
        $data['updated_by'] = $request->user()->id;

        if ($request->has('start')) {
            $start = Carbon::parse($request->start);
            $end   = Carbon::parse($request->end);

            $data['date'] = $start->toDateString();
            $data['start_time'] = $start->format('H:i:s');
            $data['end_time']   = $end->format('H:i:s');
        }

        $appointment->update($data);

        return response()->json(['success' => true]);
    }

    // /**
    //  * Update the specified resource in storage.
    //  * @param \Illuminate\Http\Request $request
    //  * @param \App\Models\Appointment      $appointment
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(AppointmentRequest $request, Appointment $appointment)
    // {
    //     $data = $request->validated();
    //     $data['updated_by'] = $request->user()->id;

    //     $appointment->update($data);

    //     return new AppointmentResource($appointment);
    // }

    /**
     * Remove the specified resource from storage.
     * @param \App\Models\Appointment $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();


        return response()->noContent();
    }

}
