<?php

namespace Database\Seeders;

use App\Models\Episode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class EpisodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = File::get('database/data/episodes.json');
        $episodes = json_decode($file);

        foreach ($episodes as $episode => $value) {
            Episode::create([
                "slug" => $value->slug,
                "name" => $value->name,
                "description" => $value->description,
                "image" => $value->image,
                "thumbnail" => $value->thumbnail,
                "release" => $value->release,
                "episode" => $value->episode
            ]);
        }
    }
}
