<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'date', 'status'];

    public function punches(){
        return $this->hasMany(AttendancePunch::class, 'attendance_id', 'id' );
    }
}
