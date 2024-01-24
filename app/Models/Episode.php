<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = ['pivot', 'created_at', 'updated_at'];


    public function characters(){
        return $this->belongsToMany(Character::class, 'characters_in_episodes', 'episode_slug', 'character_slug', 'slug', 'slug');
    }
}
