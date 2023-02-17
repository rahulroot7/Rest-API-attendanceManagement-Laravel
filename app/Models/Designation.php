<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Designation extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    protected static function boot() {
        parent::boot();

        static::creating(function ($designation) {
            $designation->name = ucwords($designation->name);
        });

        static::updating(function ($designation) {
            $designation->name = ucwords($designation->name);
        });
    }
}
