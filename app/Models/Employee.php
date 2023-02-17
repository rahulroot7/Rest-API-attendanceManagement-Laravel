<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'first_name', 'last_name', 'email', 'mobile_number', 'department_id', 'designation_id', 'state_id', 'city_id', 'avatar', 'manager_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function department(){
        return $this->belongsTo(Department::class);
    }

    public function designation(){
        return $this->belongsTo(Designation::class);
    }

    protected static function booted()
    {
        $adminRole = config('constants.user_roles.admin');
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role != $adminRole) {
                static::addGlobalScope('role', function (Builder $builder) use ($user) {
                    $builder->where('manager_id', $user->id);
//                    $builder->orwhere('user_id', $user->id );
                });
            }
        }
    }

}
