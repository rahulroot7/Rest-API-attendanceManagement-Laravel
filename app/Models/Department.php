<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    protected static function boot() {
        parent::boot();

        static::creating(function ($department) {
            $department->name = ucwords($department->name);
        });

        static::updating(function ($department) {
            $department->name = ucwords($department->name);
        });
    }
}
