<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EpisodeApiTest extends TestCase
{
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
    public function test_get_one_episode_by_id(): void
    {
        $response = $this->get('/apiV1/episodes/1');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'message',
            'item' => $this->episodeJsonStructure
        ]);
    }

    public function test_get_one_episode_by_id_with_collections(): void
    {
        $response = $this->get('/apiV1/episodes/1?includeCharacters');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'item' => [
                ...$this->episodeJsonStructure,
                'characters' => ['*' => $this->characterJsonStructure]
            ]
        ]);
    }

    public function test_get_one_episode_by_slug(): void
    {
        $response = $this->get('/apiV1/episodes/slug/slumber-party-panic');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'item' => $this->episodeJsonStructure
        ]);
    }

    public function test_get_one_episode_by_slug_with_collections(): void
    {
        $response = $this->get('/apiV1/episodes/slug/slumber-party-panic?includeCharacters');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'item' => [
                ...$this->episodeJsonStructure,
                'characters' => ['*' => $this->characterJsonStructure]
            ]
        ]);
    }

    public function test_get_all_episodes(): void
    {
        $response = $this->get('/apiV1/episodes');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'items' => ['*' => $this->episodeJsonStructure]
        ]);
    }

    public function test_get_all_episodes_paginated(): void
    {
        $response = $this->get('/apiV1/episodes/paginated');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'pagination' => [
                ...$this->paginationJsonStructure,
                'data'=> ['*' => $this->episodeJsonStructure]
            ]
        ]);
    }

    public function test_get_all_episodes_with_collections(): void
    {
        $response = $this->get('/apiV1/episodes?includeCharacters');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'items' => [
                '*' => [
                    ...$this->episodeJsonStructure,
                    'characters' => ['*' => $this->characterJsonStructure]
                ]
            ]
        ]);
    }

    public function test_get_all_episodes_paginated_with_collections(): void
    {
        $response = $this->get('/apiV1/episodes/paginated?includeCharacters');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'pagination' => [
                ...$this->paginationJsonStructure,
                'data' => [
                    '*' => [
                        ...$this->episodeJsonStructure,
                        'characters' => ['*' => $this->characterJsonStructure]
                    ]
                ]
            ]
        ]);
    }

    public function test_get_multiple_episodes_by_id(): void
    {
        $response = $this->get('/apiV1/episodes/1,2,3');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'items' => ['*' => $this->episodeJsonStructure]
        ]);
    }

    public function test_get_multiple_episodes_by_id_with_collections(): void
    {
        $response = $this->get('/apiV1/episodes/1,2,3?includeCharacters');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'items' => [
                '*' => [
                    ...$this->episodeJsonStructure,
                    'characters' => ['*' => $this->characterJsonStructure]
                ]
            ]
        ]);
    }


}
