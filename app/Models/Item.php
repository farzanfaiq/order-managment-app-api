<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $table = 'items';
    protected $fillable = ['name', 'description', 'price', 'tax', 'discount', 'categroy_id', 'image', 'user_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function categories(){
        return $this->belongsTo(Category::class);
    }
}
