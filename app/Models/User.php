<?php

namespace App\Models;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['employee_code', 'name', 'email', 'password', 'role'];

    function employee()
    {
        return $this->hasOne(Employee::class)->withoutGlobalScopes();
    }

    function attendances(){
        return $this->hasMany(Attendance::class, 'attendances', 'user_id', 'id');
    }

    function team(){
        return $this->hasMany(Employee::class, 'manager_id', 'id');
    }

    protected static function booted()
    {
        $adminRole = config('constants.user_roles.admin');
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role != $adminRole) {
                static::addGlobalScope('role', function (Builder $builder) use ($user) {
                    $builder->with('employee');
                    $builder ->whereHas('employee', function($q) use ($user) {
                        $q->where('manager_id', $user->id);
                        $q->orWhere('user_id', $user->id);
                    });
                });
            }
        }
    }

}//end of,class
