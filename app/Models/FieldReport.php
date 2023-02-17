<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FieldReport extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'date', 'working_hrs', 'travelled_km', 'stockiest_met', 'distributor_met', 'chemist_met'];

    function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
