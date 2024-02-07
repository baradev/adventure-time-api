<?php

namespace Tests\Feature;

use Tests\TestCase;

class CharacterApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    private $characterJsonStructure = [
        "id",
        "slug",
        "name",
        "full_name",
        "description",
        "specie",
        "quotes",
        "image",
        "thumbnail",
        "kingdom_slug"
    ];

    private $kingdomJsonStructure = [
        "id",
        "slug",
        "name",
        "description",
        "image",
        "thumbnail"
    ];

    private $episodeJsonStructure = [
        "id",
        "slug",
        "name",
        "description",
        "image",
        "thumbnail",
        "release",
        "episode",
    ];

    private $paginationJsonStructure = [
        'current_page',
        'first_page_url',
        'from',
        'last_page',
        'last_page_url',
        'links' => ['*' => ['url', 'label', 'active']],
        'next_page_url',
        'path',
        'per_page',
        'prev_page_url',
        'to',
        'total'
    ];

    public function test_get_character_by_id(): void
    {
        $response = $this->get('/apiV1/characters/1');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'item' => $this->characterJsonStructure
        ]);
    }

    public function test_get_character_by_id_with_collections(): void
    {
        $response = $this->get('/apiV1/characters/1?includeKingdom');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'item' => [
                ...$this->characterJsonStructure,
                "kingdom" => $this->kingdomJsonStructure
            ]
        ]);

        $response = $this->get('/apiV1/characters/1?includeEpisodes');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'item' => [
                ...$this->characterJsonStructure,
                "episodes" => ['*' => $this->episodeJsonStructure]
            ]
        ]);

        $response = $this->get('/apiV1/characters/1?includeEpisodes&includeKingdom');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'item' => [
                ...$this->characterJsonStructure,
                "episodes" => ['*' => $this->episodeJsonStructure],
                "kingdom" => $this->kingdomJsonStructure
            ]
        ]);
    }

    public function test_get_character_by_slug(): void
    {
        $response = $this->get('/apiV1/characters/slug/finn');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'item' => $this->characterJsonStructure
        ]);
    }

    public function test_get_character_by_slug_with_collections(): void
    {
        $response = $this->get('/apiV1/characters/slug/finn?includeKingdom');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'item' => [
                ...$this->characterJsonStructure,
                "kingdom" => $this->kingdomJsonStructure
            ]
        ]);

        $response = $this->get('/apiV1/characters/slug/finn?includeEpisodes');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'item' => [
                ...$this->characterJsonStructure,
                "episodes" => ['*' => $this->episodeJsonStructure]
            ]
        ]);

        $response = $this->get('/apiV1/characters/slug/finn?includeEpisodes&includeKingdom');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'item' => [
                ...$this->characterJsonStructure,
                "episodes" => ['*' => $this->episodeJsonStructure],
                "kingdom" => $this->kingdomJsonStructure
            ]
        ]);
    }

    public function test_get_all_characters(): void
    {
        $response = $this->get('/apiV1/characters');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'items' => [
                '*' => $this->characterJsonStructure
            ]
        ]);
    }


    public function test_get_all_characters_with_collections(): void
    {
        $response = $this->get('/apiV1/characters?includeKingdom');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'items' => [
                '*' => [
                    ...$this->characterJsonStructure,
                    "kingdom" => $this->kingdomJsonStructure
                ]
            ]
        ]);

        $response = $this->get('/apiV1/characters?includeEpisodes');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'items' => [
                '*' => [
                    ...$this->characterJsonStructure,
                    "episodes" => ['*' => $this->episodeJsonStructure]
                ]
            ]
        ]);


        $response = $this->get('/apiV1/characters?includeEpisodes&includeKingdom');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'items' => [
                '*' => [
                    ...$this->characterJsonStructure,
                    "episodes" => ['*' => $this->episodeJsonStructure],
                    "kingdom" => $this->kingdomJsonStructure
                ]
            ]
        ]);
    }

    // name, fullname, specie
    public function test_get_all_filter_params()
    {
        $charactersFiltered = $this->get('/apiV1/characters?name=f');
        $charactersFiltered->assertStatus(200);

        // is really filtered
        $charactersFiltered->assertJsonStructure([
            'message',
            'items' => [
                '*' => $this->characterJsonStructure
            ]
        ]);

        foreach ($charactersFiltered->json()['items'] as $character) {
            $this->assertStringContainsString(strtolower('f'), strtolower($character['name']));
        }


        $charactersFiltered = $this->get('/apiV1/characters?full_name=f');
        $charactersFiltered->assertStatus(200);

        // is really filtered
        $charactersFiltered->assertJsonStructure([
            'message',
            'items' => [
                '*' => $this->characterJsonStructure
            ]
        ]);

        foreach ($charactersFiltered->json()['items'] as $character) {
            $this->assertStringContainsString(strtolower('f'), strtolower($character['full_name']));
        }


        $charactersFiltered = $this->get('/apiV1/characters?specie=human');
        $charactersFiltered->assertStatus(200);

        // is really filtered
        $charactersFiltered->assertJsonStructure([
            'message',
            'items' => [
                '*' => $this->characterJsonStructure
            ]
        ]);

        foreach ($charactersFiltered->json()['items'] as $character) {
            $this->assertStringContainsString(strtolower('human'), strtolower($character['specie']));
        }
    }

    public function test_get_all_characters_paginated(): void
    {
        $response = $this->get('/apiV1/characters/paginated');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'pagination' => [
                'data' => ['*' => $this->characterJsonStructure],
                ...$this->paginationJsonStructure
            ]
        ]);
    }


    public function test_get_all_characters_paginated_with_collections(): void
    {
        $response = $this->get('/apiV1/characters/paginated?includeKingdom');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'pagination' => [
                'data' => ['*' => [...$this->characterJsonStructure, 'kingdom' => $this->kingdomJsonStructure]],
                ...$this->paginationJsonStructure
            ]
        ]);

        $response = $this->get('/apiV1/characters/paginated?includeEpisodes');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'pagination' => [
                'data' => ['*' => [...$this->characterJsonStructure, 'episodes' => ['*' => $this->episodeJsonStructure]]],
                ...$this->paginationJsonStructure
            ]
        ]);


        $response2 = $this->get('/apiV1/characters/paginated?includeEpisodes&includeKingdom');
        $response2->assertStatus(200);
        $response2->assertJsonStructure([
            'message',
            'pagination' => [
                'data' => [
                    '*' => [
                        ...$this->characterJsonStructure,
                        'kingdom' => $this->kingdomJsonStructure,
                        'episodes' => ['*' => $this->episodeJsonStructure]
                    ],
                ],
                ...$this->paginationJsonStructure
            ]
        ]);
    }

    // name, fullname, specie
    public function test_get_all_paginated_filter_params()
    {
        $charactersFiltered = $this->get('/apiV1/characters/paginated?name=f');
        $charactersFiltered->assertStatus(200);

        // is really filtered
        $charactersFiltered->assertJsonStructure([
            'message',
            'pagination' => [
                'data' => ['*' => $this->characterJsonStructure],
                ...$this->paginationJsonStructure
            ]
        ]);

        foreach ($charactersFiltered->json()['pagination']['data'] as $character) {
            $this->assertStringContainsString(strtolower('f'), strtolower($character['name']));
        }


        $charactersFiltered = $this->get('/apiV1/characters/paginated?full_name=f');
        $charactersFiltered->assertStatus(200);

        // is really filtered
        $charactersFiltered->assertJsonStructure([
            'message',
            'pagination' => [
                'data' => ['*' => $this->characterJsonStructure],
                ...$this->paginationJsonStructure
            ]
        ]);

        foreach ($charactersFiltered->json()['pagination']['data'] as $character) {
            $this->assertStringContainsString(strtolower('f'), strtolower($character['full_name']));
        }


        $charactersFiltered = $this->get('/apiV1/characters/paginated?specie=human');
        $charactersFiltered->assertStatus(200);

        // is really filtered
        $charactersFiltered->assertJsonStructure([
            'message',
            'pagination' => [
                'data' => ['*' => $this->characterJsonStructure],
                ...$this->paginationJsonStructure
            ]
        ]);

        foreach ($charactersFiltered->json()['pagination']['data'] as $character) {
            $this->assertStringContainsString(strtolower('human'), strtolower($character['specie']));
        }
    }

    public function test_get_multiple_characters_by_id(): void
    {
        $response = $this->get('/apiV1/characters/1,2,3');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'items' => ['*' => $this->characterJsonStructure]
        ]);
    }

    public function test_get_multiple_characters_by_id_with_collections(): void
    {
        $response = $this->get('/apiV1/characters/1,2,3?includeKingdom');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'items' => ['*' => [
                ...$this->characterJsonStructure,
                "kingdom" => $this->kingdomJsonStructure
            ]]
        ]);

        $response = $this->get('/apiV1/characters/1,2,3?includeEpisodes');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'items' => ['*' => [
                ...$this->characterJsonStructure,
                "episodes" => ['*' => $this->episodeJsonStructure]
            ]]
        ]);

        $response = $this->get('/apiV1/characters/1,2,3?includeEpisodes&includeKingdom');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'items' => ['*' => [
                ...$this->characterJsonStructure,
                "episodes" => ['*' => $this->episodeJsonStructure],
                "kingdom" => $this->kingdomJsonStructure
            ]]
        ]);
    }
}
