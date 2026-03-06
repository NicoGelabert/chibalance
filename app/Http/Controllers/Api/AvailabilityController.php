<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\Timeslot;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AvailabilityController extends Controller
{
    use App\Models\Timeslot;

public function availability(Request $request)
{
    $date = $request->query('date');
    $productId = $request->query('product_id');

    if (!$date) {
        return response()->json(['error' => 'Missing date parameter'], 400);
    }

    // Definir slots automáticos según el día de la semana
    $dayOfWeek = Carbon::parse($date)->dayOfWeek;
    switch ($dayOfWeek) {
        case 1: $allSlots = ['10:00', '11:15', '12:45', '14:00']; break;
        case 2: $allSlots = ['18:00', '19:15']; break;
        case 4: $allSlots = ['10:00', '11:15', '12:45', '14:00']; break;
        case 5: $allSlots = ['18:00', '19:15']; break;
        case 6: $allSlots = ['10:00', '11:15']; break;
        default: $allSlots = []; break;
    }

    $slots = [];
    foreach ($allSlots as $start) {
        $end = Carbon::parse("$date $start")->addHour()->format('H:i');

        // ⚡ Esto crea el slot en DB si no existe
        $slot = Timeslot::firstOrCreate(
            [
                'date' => $date,
                'start_time' => $start,
                'end_time' => $end,
                'product_id' => $productId,
            ]
        );

        $slots[] = [
            'id' => $slot->id, // ✅ Asegurarse de enviar ID real
            'date' => $slot->date,
            'start_time' => $slot->start_time,
            'end_time' => $slot->end_time
        ];
    }


    return response()->json([
        'slots' => $slots
    ]);
}

}
