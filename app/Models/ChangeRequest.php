<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeRequest extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'approver_id', 'type', 'date', 'in_time', 'out_time', 'remark'];

    function user(){
        return $this->belongsTo(User::class);
    }
}
