<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleCalendarToken extends Model
{
    protected $table = 'google_calendar_tokens';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'access_token',
        'refresh_token',
        'expires_at',
    ];
}
