<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
    use HasFactory;
    protected $table = 'permissions';
    protected $fillable = ['name', 'slug'];

    public function roles(){
        return $this->belongsToMany(Role::class, 'role_permissions');
    }

    
    public function user(){
        return $this->belongsToMany(User::class, '	user_permissions');
    }
}
