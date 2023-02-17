<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendancePunch extends Model
{
    use HasFactory;

    protected $fillable = ['attendance_id', 'time', 'punch_type', 'latitude', 'longitude'];

//    public function attendance(){
//        return $this->belongsTo(Attendance::class, 'attendance_punches', 'id', 'attendance_id');
//    }
}
