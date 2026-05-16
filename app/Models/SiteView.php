<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteView extends Model
{
    use HasFactory;

    protected $table = 'site_views';

    protected $fillable = [
        'ip_address',
        'user_agent',
        'page_url',
        'country',
    ];
}
