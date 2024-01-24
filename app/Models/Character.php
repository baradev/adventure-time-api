<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    use HasFactory;

    protected $guarded = [];

    
    protected $casts = [
        'quotes' => "array"
    ];
    
    public function episodes(){
        return $this->belongsToMany(Episode::class);
    }

    public function kingdom(){
        return $this->belongsTo(Kingdom::class);
    }
}
