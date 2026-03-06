<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timeslot extends \App\Models\Timeslot
{
    public function getRouteKeyName()
    {
        return 'id';
    }
}
