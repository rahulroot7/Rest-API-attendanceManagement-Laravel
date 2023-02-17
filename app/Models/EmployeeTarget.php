<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeTarget extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'date', 'year', 'month', 'target'];

    function user(){
        return $this->belongsTo(User::class);
    }
}
