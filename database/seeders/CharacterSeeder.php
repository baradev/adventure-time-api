<?php

namespace Database\Seeders;

use App\Helpers\CharacterValidator;
use App\Models\Character;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CharacterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     *
     */

    // validate if any field is missing.

    // validate if type of data is correct.

    // validate if data is correct.

    private function throwError(string $error, string $itemString)
    {
        throw new \Exception($error . ": " . $itemString);
    }


    private function validateAnyFieldMissing($item): bool
    {
        $json = json_encode($item, JSON_PRETTY_PRINT);
        if (!isset($item?->slug)) $this->throwError("Missing slug", $json);
        if (!isset($item?->name)) $this->throwError("Missing name", $json);
        if (!isset($item?->full_name)) $this->throwError("Missing full name", $json);
        if (!isset($item?->description)) $this->throwError("Missing description", $json);
        if (!isset($item?->specie)) $this->throwError("Missing specie", $json);
        if (!isset($item?->quotes)) $this->throwError("Missing quotes", $json);
        if (!isset($item?->image)) $this->throwError("Missing image", $json);
        if (!isset($item?->thumbnail)) $this->throwError("Missing thumbnail", $json);
        if (!isset($item?->kingdom_slug)) $this->throwError("Missing kingdom slug", $json);

        return true;
    }


    private function validateTypeOfItem($item): bool
    {
        $json = json_encode($item, JSON_PRETTY_PRINT);
        if (gettype($item->slug) !== "string") $this->throwError("Invalid type of slug, it musts be string", $json);
        if (gettype($item->name) !== "string") $this->throwError("Invalid type of name, it musts be string", $json);
        if (gettype($item->full_name) !== "string") $this->throwError("Invalid type of full name, it musts be string", $json);
        if (gettype($item->description) !== "string") $this->throwError("Invalid type of description, it musts be string", $json);
        if (gettype($item->specie) !== "string") $this->throwError("Invalid type of specie, it musts be string", $json);
        if (gettype($item->quotes) !== "array") $this->throwError("Invalid type of quotes, it musts be array", $json);
        // validate if quotes is an array, validate each quote.
        foreach ($item->quotes as $quote) {
            if (gettype($quote) !== "string") $this->throwError("Invalid type of quote, it musts be string each quote", $json);
        }
        if (gettype($item->image) !== "string") $this->throwError("Invalid type of image, it musts be string", $json);
        if (gettype($item->thumbnail) !== "string") $this->throwError("Invalid type of thumbnail, it musts be string", $json);
        if (gettype($item->kingdom_slug) !== "string") $this->throwError("Invalid type of kingdom slug, it musts be string", $json);
        return true;
    }

    private function validateDataFormat($item): bool
    {
        $json = json_encode($item, JSON_PRETTY_PRINT);
        if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/i', $item->slug)) $this->throwError("Invalid slug, it must be a string with this pattern: /^[a-z0-9]+(?:-[a-z0-9]+)*$/i", $json);
        if (strlen($item->slug) < 1 || strlen($item->slug) > 100) $this->throwError("Invalid slug, it must be between 1 and 100 characters", $json);
        if (strlen($item->name) < 1 || strlen($item->name) > 100) $this->throwError("Invalid name, it must be between 1 and 100 characters", $json);
        if (strlen($item->full_name) < 1 || strlen($item->full_name) > 100) $this->throwError("Invalid full name, it must be between 1 and 100 characters", $json);
        if (strlen($item->description) < 1 || strlen($item->description) > 800) $this->throwError("Invalid description, it must be between 1 and 800 characters", $json);
        if (strlen($item->specie) < 1 || strlen($item->specie) > 100) $this->throwError("Invalid specie, it must be between 1 and 100 characters", $json);
        // if quotes is an array, validate each quote.
        foreach ($item->quotes as $quote) {
            if (strlen($quote) < 1 || strlen($quote) > 100) $this->throwError("Invalid quote, it must be between 1 and 100 characters", $json);
        }
        if (strlen($item->image) < 1 || strlen($item->image) > 100) $this->throwError("Invalid image, it must be between 1 and 100 characters", $json);
        // image path must start with /assets/images/characters/
        if (strpos($item->image, '/assets/images/characters/') !== 0) $this->throwError("Invalid image path, it must start with '/assets/images/characters/'", $json);

        if (strlen($item->thumbnail) < 1 || strlen($item->thumbnail) > 100) $this->throwError("Invalid thumbnail, it must be between 1 and 100 characters", $json);
        // thumbnail path must start with /assets/images/characters/
        if (strpos($item->thumbnail, '/assets/images/characters/') !== 0) $this->throwError("Invalid thumbnail path, it must start with '/assets/images/characters/'", $json);

        if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/i', $item->kingdom_slug)) $this->throwError("Invalid kingdom slug, it must be a string with this pattern: /^[a-z0-9]+(?:-[a-z0-9]+)*$/i", $json);

        return true;
    }


    public function validateEntry($item): bool
    {
        $this->validateAnyFieldMissing($item);
        $this->validateTypeOfItem($item);
        $this->validateDataFormat($item);
        return true;
    }

    public function run(): void
    {
        $file = File::get('database/data/characters.json');
        $characters = json_decode($file);
        $appUrl = env('APP_URL');

        foreach ($characters as $character => $value) {
            $this->validateEntry($value);
            Character::create([
                "slug" => $value->slug,
                "name" => $value->name,
                "description" => $value->description,
                "full_name" => $value->full_name,
                "specie" => $value->specie,
                "quotes" => $value->quotes,
                "thumbnail" => $appUrl . $value->thumbnail,
                "image" => $appUrl . $value->image,
                "kingdom_slug" => $value->kingdom_slug,
            ]);
        }
    }
}
