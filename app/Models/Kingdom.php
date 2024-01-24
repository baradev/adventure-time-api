<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kingdom extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = ['pivot', 'created_at', 'updated_at'];


    public function characters(){
        return $this->hasMany(Character::class, 'kingdom_slug', 'slug');
    }
}
