<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KingdomApiTest extends TestCase
{

    // use DatabaseMigrations;

    // protected $seed = true;

    
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

    public function test_get_one_episode_by_id(): void
    {
        $response = $this->get('/apiV1/kingdoms/1');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'item' => $this->kingdomJsonStructure
        ]);
    }

    public function test_get_one_episode_by_id_with_collections(): void
    {
        $response = $this->get('/apiV1/kingdoms/1?includeCharacters');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'item' => [
                ...$this->kingdomJsonStructure,
                'characters' => ['*' => $this->characterJsonStructure]
            ]
        ]);
    }

    public function test_get_one_episode_by_slug(): void
    {
        $response = $this->get('/apiV1/kingdoms/slug/candy-kingdom');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'item' => $this->kingdomJsonStructure
        ]);
    }

    public function test_get_one_episode_by_slug_with_collections(): void
    {
        $response = $this->get('/apiV1/kingdoms/slug/candy-kingdom?includeCharacters');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'item' => [
                ...$this->kingdomJsonStructure,
                'characters' => ['*' => $this->characterJsonStructure]
            ]
        ]);
    }

    public function test_get_all_episodes(): void
    {
        $response = $this->get('/apiV1/kingdoms');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'items' => [
                '*' => $this->kingdomJsonStructure
            ]
        ]);
    }

    public function test_get_all_with_collections(): void
    {
        $response = $this->get('/apiV1/kingdoms?includeCharacters');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'items' => [
                '*' => [
                    ...$this->kingdomJsonStructure,
                    'characters' => [
                        '*' => $this->characterJsonStructure
                    ]
                ]
            ]
        ]);
    }


    public function test_get_all_episodes_paginated(): void
    {
        $response = $this->get('/apiV1/kingdoms/paginated');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'message',
            'pagination' => [
                ...$this->paginationJsonStructure,
                'data' => ['*' => $this->kingdomJsonStructure,]
            ],
        ]);
    }

    public function test_get_all_episodes_paginated_with_collections(): void
    {
        $response = $this->get('/apiV1/kingdoms/paginated?includeCharacters');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'message',
            'pagination' => [
                ...$this->paginationJsonStructure,
                'data' => [
                    '*' => [
                        ...$this->kingdomJsonStructure,
                        'characters' => ['*' => $this->characterJsonStructure]
                    ]
                ],
            ],
        ]);
    }

    public function test_get_multiple_episodes_by_ids(): void
    {
        $response = $this->get('/apiV1/kingdoms/1,2,3');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'items' => ['*' => $this->kingdomJsonStructure]
        ]);
    }

    public function test_get_multiple_episodes_by_ids_with_collections(): void
    {

        $response = $this->get('/apiV1/kingdoms/1,2,3?includeCharacters');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'items' => [
                '*' => [
                    ...$this->kingdomJsonStructure,
                    'characters' => ['*' => $this->characterJsonStructure]
                ]
            ]
        ]);
    }

    
}
