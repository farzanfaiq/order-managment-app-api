<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaManager extends Model
{
    use HasFactory;
    
    protected $table = 'area-manager';
    protected $fillable = ['name', 'email', 'phone', 'zip_code', 'area_name', 'picture'];
}
