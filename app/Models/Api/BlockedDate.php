<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedDate extends \App\Models\BlockedDate
{
    public function getRouteKeyName()
    {
        return 'id';
    }
}
