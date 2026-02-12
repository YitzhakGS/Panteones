<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rol extends Model
{
    
    protected $table = 'rol';

    protected $fillable = [
        'name',
       
    ];

    public function rols()
    {
        return $this->belongsTo(User::class,'rol');
    }
}