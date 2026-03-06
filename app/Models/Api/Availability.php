<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availability extends \App\Models\Availability
{
    public function getRouteKeyName()
    {
        return 'id';
    }
}
