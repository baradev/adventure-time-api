<?php

namespace Database\Seeders;

use App\Models\Character;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CharacterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = File::get('database/data/characters.json');
        $characters = json_decode($file);

        foreach ($characters as $character => $value) {
            Character::create([
                    "slug" => $value->slug,
                    "name" => $value->name,
                    "description" => $value->description,
                    "full_name" => $value->full_name,
                    "specie" => $value->specie,
                    "quotes" => $value->quotes,
                    "thumbnail" => $value->thumbnail,
                    "image" => $value->image,
                    "kingdom_slug" => $value->kingdom_slug,
            ]);
        }
    }
}
