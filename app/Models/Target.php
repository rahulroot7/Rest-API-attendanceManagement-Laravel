<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Target extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'year', 'month', 'target', 'achieve_target'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
