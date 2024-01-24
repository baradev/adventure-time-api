<?php

namespace Database\Seeders;

use App\Models\Kingdom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class KingdomSeeder extends Seeder
{

    public function run(): void
    {

        $file = File::get('database/data/kingdoms.json');
        $kingdoms = json_decode($file, true);

        foreach ($kingdoms as $kingdom => $value) {
            Kingdom::create([
                'slug' => $value['slug'],
                'name' => $value['name'],
                'description' => $value['description'],
                'thumbnail' => $value['thumbnail'],
                'image' => $value['image'],
            ]);
        }
    }
}
