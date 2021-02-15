<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    use HasFactory;

    public function creator(){
        return $this->belongsTo(User::class);
    }

    public function accessLogs(){
        return $this->hasMany(AccessLog::class);
    }

    public function getRouteKeyName()
    {
        return 'short_url';
    }
}
