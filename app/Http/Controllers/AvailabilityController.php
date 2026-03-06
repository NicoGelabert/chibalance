<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\Appointment;
use App\Models\Product;
use App\Models\BlockedDate;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AvailabilityController extends Controller
{
    public function index(Request $request)
    {

        $date = $request->get('date'); // YYYY-MM-DD
        $productId = $request->get('product_id');

        $product = Product::findOrFail($productId);

        $day = Carbon::parse($date)->dayOfWeek; // 0 = Sunday, 6 = Saturday

        // ✅ Comprobamos si el día está bloqueado
        if (BlockedDate::where('date', $date)->exists()) {
            return response()->json([
                'available' => [],
                'booked' => []
            ]);
        }

        // Horarios de atención
        $opening = Carbon::parse($date.' 10:00');
        $closing = Carbon::parse($date.' 17:00');
        $lunchStart = Carbon::parse($date.' 13:00');
        $lunchEnd = Carbon::parse($date.' 14:00');

        $slots = [];
        $current = $opening->copy();

        while ($current->lessThanOrEqualTo($closing)) {
            // Saltar almuerzo
            if ($current->between($lunchStart, $lunchEnd->subSecond())) {
                $current->addHour();
                continue;
            }
            $slots[] = $current->format('H:i');
            $current->addHour();
        }

        // Turnos ya reservados
        $booked = Appointment::where('product_id', $productId)
            ->where('date', $date)
            ->whereNull('deleted_at') // por si hay soft deletes
            ->whereRaw("TRIM(LOWER(status)) NOT IN ('cancelled','rejected')")
            ->pluck('start_time')
            ->map(fn($time) => Carbon::parse($time)->format('H:i'))
            ->toArray();

        // Slots disponibles
        $available = array_values(array_diff($slots, $booked));

        return response()->json([
            'all' => $slots,
            'available' => $available,
            'booked' => $booked,
        ]);
    }

    public function getAvailability(Request $request)
    {
        $date = $request->query('date');

        if (!$date) {
            return response()->json(['booked' => []]);
        }

        $carbonDate = Carbon::parse($date);
        $dayOfWeek = $carbonDate->dayOfWeek; // 0 = domingo, 6 = sábado

        // Bloquear fines de semana
        if ($dayOfWeek === 0) {
            return response()->json(['booked' => [], 'message' => 'No hay disponibilidad los fines de semana.']);
        }

        // Generar todos los slots automáticos
        $allSlots = [];
        for ($hour = 10; $hour <= 16; $hour++) {
            if ($hour === 13) continue; // Almuerzo 13-14
            $allSlots[] = sprintf("%02d:00", $hour);
        }

        // Obtener citas ya reservadas para esa fecha
        $bookedAppointments = Appointment::whereDate('date', $carbonDate->format('Y-m-d'))
            ->where('status', '!=', 'cancelled')  // ⚠ solo citas activas
            ->get();
        $booked = $bookedAppointments->pluck('start_time')->map(function($time) {
            return substr($time, 0, 5); // 'HH:MM'
        })->toArray();

        return response()->json([
            'booked' => $booked,
            'allSlots' => $allSlots,
        ]);
    }
    
    public function availability(Request $request)
    {
        $date = $request->query('date');
        $productId = $request->query('product_id');

        // Verificar formato y existencia del parámetro date
        if (!$date) {
            return response()->json(['error' => 'Missing date parameter'], 400);
        }

        // Convertir a Carbon para obtener el día de la semana
        $dayOfWeek = Carbon::parse($date)->dayOfWeek; 
        // 0=domingo, 1=lunes, 2=martes, 3=miércoles, 4=jueves, 5=viernes, 6=sábado

        // ⚙️ Definir horarios según el día
        switch ($dayOfWeek) {
            case 1: // lunes
            case 4: // jueves
                $allSlots = ['10:00', '11:15', '12:45', '14:00'];
                break;

            case 2: // martes
            case 5: // viernes
                $allSlots = ['18:00', '19:15'];
                break;

            case 6: // sábado
                $allSlots = ['10:00', '11:15'];
                break;

            default: // otros días
                $allSlots = [];
                break;
        }

        // Consultar turnos reservados para ese producto/fecha
        $bookedSlots = Appointment::where('product_id', $productId)
            ->where('date', $date)
            ->whereRaw("TRIM(LOWER(status)) != 'cancelled'") // ⚡ filtra cancelados
            ->pluck('start_time')
            ->map(fn($time) => substr($time, 0, 5)) // "HH:MM"
            ->toArray();

        // Calcular disponibles
        $availableSlots = array_values(array_diff($allSlots, $bookedSlots));

        return response()->json([
            'all' => $allSlots,
            'available' => $availableSlots,
            'booked' => $bookedSlots,
        ]);
    }

}
