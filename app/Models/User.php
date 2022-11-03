<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Traits\HasPermissionTrait;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasPermissionTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'gender'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function assignRole(...$slug){
        $role = Roles::where('slug',$slug)->first();
        if($role){
            $this->roles()->attach($role);
        } 
    }

    public function roles()
    {
        return $this->belongsToMany(Roles::class, UserRole::class, 'user_id', 'role_id', '');
    }

}
