<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class spinner extends Model
{
    use HasFactory;
    protected $fillable = [
        'phone_number',// unique phone number
        'score',
        'ip_address',
        'user_agent',// mobile or desktop
        'played_count', // number of times the user has played
        

    ];
}
