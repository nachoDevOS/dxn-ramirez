<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SendWhatsapp extends Model
{
    use HasFactory;
    protected $fillable = [
        'server',
        'session',
        'country_code',
        'phone',
        'message',
        'type',
        'status',
    ];
}
