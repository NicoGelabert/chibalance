<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use SoftDeletes;

    protected $fillable = [
    'first_name',
    'last_name',
    'email',
    'contact_number',
    'product_id',
    'date',
    'start_time',
    'end_time',
    'status',
    'notes',
    'cancel_token',
    'created_by',
    'updated_by'
    ];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
