<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = ['pivot', 'created_at', 'updated_at'];
    
    protected $casts = [
        'quotes' => "array"
    ];
    
    public function episodes(){
    // $table = null,
    // $foreignPivotKey = null,
    // $relatedPivotKey = null,
    // $parentKey = null,
    // $relatedKey = null,
    // $relation = nul
        return $this->belongsToMany(Episode::class, 'characters_in_episodes', 'character_slug', 'episode_slug', 'slug', 'slug');
    }

    public function kingdom(){
        // relation by slug
        return $this->belongsTo(Kingdom::class, 'kingdom_slug', 'slug');
    }
}
