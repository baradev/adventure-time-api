<?php

namespace Database\Seeders;

use App\Models\Episode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        $appUrl = env('APP_URL');

        foreach ($episodes as $episode => $value) {
            Episode::create([
                "slug" => $value->slug,
                "name" => $value->name,
                "description" => $value->description,
                "image" => $appUrl.$value->image,
                "thumbnail" => $appUrl.$value->thumbnail,
                "release" => $value->release,
                "episode" => $value->episode
            ]);

            // characters_slug and episodes_slug are the foreign keys, for table character_in_episodes
            $character_slugs = $value->character_slugs;

            foreach ($character_slugs as $character_slug) {
                DB::table('characters_in_episodes')->insert([
                    "character_slug" => $character_slug,
                    "episode_slug" => $value->slug
                ]);
            }
        }
    }
}
