<?php

namespace Database\Seeders;

use App\Models\Episode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use function App\Helpers\isValidSlug;

class EpisodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    //   "slug": "slumber-party-panic",
    // "name": "Slumber Party Panic",
    // "description": "Finn and Jake must save Princess Bubblegum from an impending zombie attack.",
    // "image": "/assets/images/episodes/slumber-party-panic.webp",
    // "thumbnail": "/assets/images/episodes/slumber-party-panic-thumbnail.webp",
    // "release": "2010-04-05",
    // "episode": "S01E01",
    // "character_slugs": ["finn", "jake"]

    private function throwError(string $error, string $itemString)
    {
        throw new \Exception($error . ": " . $itemString);
    }


    private function validateAnyFieldMissing($item): bool
    {
        $json = json_encode($item, JSON_PRETTY_PRINT);
        if (!isset($item?->slug)) $this->throwError("Missing slug", $json);
        if (!isset($item?->name)) $this->throwError("Missing name", $json);
        if (!isset($item?->description)) $this->throwError("Missing description", $json);
        if (!isset($item?->image)) $this->throwError("Missing image", $json);
        if (!isset($item?->thumbnail)) $this->throwError("Missing thumbnail", $json);
        if (!isset($item?->release)) $this->throwError("Missing release", $json);
        if (!isset($item?->episode)) $this->throwError("Missing episode", $json);
        if (!isset($item?->character_slugs)) $this->throwError("Missing character slugs", $json);
    
        return true;
    }


    private function validateTypeOfItem($item): bool
    {
        $json = json_encode($item, JSON_PRETTY_PRINT);
        if (gettype($item->slug) !== "string") $this->throwError("Invalid type of slug, it musts be string", $json);
        if (gettype($item->name) !== "string") $this->throwError("Invalid type of name, it musts be string", $json);
        if (gettype($item->description) !== "string") $this->throwError("Invalid type of description, it musts be string", $json);
        if (gettype($item->image) !== "string") $this->throwError("Invalid type of image, it musts be string", $json);
        if (gettype($item->thumbnail) !== "string") $this->throwError("Invalid type of thumbnail, it musts be string", $json);
        if (gettype($item->release) !== "string") $this->throwError("Invalid type of release, it musts be string", $json);
        if (gettype($item->episode) !== "string") $this->throwError("Invalid type of episode, it musts be string", $json);
        if (gettype($item->character_slugs) !== "array") $this->throwError("Invalid type of character slugs, it musts be array", $json);
        // validate if character_slugs is an array, validate each character_slug.
        foreach ($item->character_slugs as $character_slug) {
            if (gettype($character_slug) !== "string") $this->throwError("Invalid type of character slug, it musts be string each character slug", $json);
        }
        return true;
    }

    private function validateDataFormat($item): bool
    {
        $json = json_encode($item, JSON_PRETTY_PRINT);
        if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/i', $item->slug)) $this->throwError("Invalid slug, it must be a string with this pattern: /^[a-z0-9]+(?:-[a-z0-9]+)*$/i", $json);
        if (strlen($item->slug) < 1 || strlen($item->slug) > 100) $this->throwError("Invalid slug, it must be between 1 and 100 characters", $json);
        if (strlen($item->name) < 1 || strlen($item->name) > 100) $this->throwError("Invalid name, it must be between 1 and 100 characters", $json);
        if (strlen($item->description) < 1 || strlen($item->description) > 800) $this->throwError("Invalid description, it must be between 1 and 800 characters", $json);
        
        if (strlen($item->image) < 1 || strlen($item->image) > 100) $this->throwError("Invalid image, it must be between 1 and 100 characters", $json);
        // image path must start with /assets/images/episodes/
        if (strpos($item->image, '/assets/images/episodes/') !== 0) $this->throwError("Invalid image path, it must start with '/assets/images/episodes/'", $json);
        
        if (strlen($item->thumbnail) < 1 || strlen($item->thumbnail) > 100) $this->throwError("Invalid thumbnail, it must be between 1 and 100 characters", $json);
        // thumbnail path must start with /assets/images/episodes/
        if (strpos($item->thumbnail, '/assets/images/episodes/') !== 0) $this->throwError("Invalid thumbnail path, it must start with '/assets/images/episodes/'", $json);

        
        if (strlen($item->release) < 1 || strlen($item->release) > 100) $this->throwError("Invalid release, it must be between 1 and 100 characters", $json);
        if (strlen($item->episode) < 1 || strlen($item->episode) > 100) $this->throwError("Invalid episode, it must be between 1 and 100 characters", $json);
        // if character_slugs is an array, validate each character_slug.
        foreach ($item->character_slugs as $character_slug) {
            if (strlen($character_slug) < 1 || strlen($character_slug) > 100) $this->throwError("Invalid character slug, it must be between 1 and 100 characters", $json);
        }
        

        return true;
    }


    public function validateEntry($item): bool
    {
        $this->validateAnyFieldMissing($item);
        $this->validateTypeOfItem($item);
        $this->validateDataFormat($item);
        return true;
    }

    // ----------------------
    public function run(): void
    {
        $file = File::get('database/data/episodes.json');
        $episodes = json_decode($file);
        $appUrl = env('APP_URL');

        foreach ($episodes as $episode => $value) {
            
            $this->validateEntry($value);

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
