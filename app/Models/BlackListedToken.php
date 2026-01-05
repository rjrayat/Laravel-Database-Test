<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlackListedToken extends Model
{
    protected $table = 'blacklisted_tokens'; // 👈 FIX

    protected $fillable = ['token', 'expires_at'];
}
