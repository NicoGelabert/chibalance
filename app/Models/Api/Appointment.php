<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends \App\Models\Appointment
{
    public function getRouteKeyName()
    {
        return 'id';
    }
}
