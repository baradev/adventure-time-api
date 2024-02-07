<?php

namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SetUpProjectTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_has_tables()
    {
        $this->assertDatabaseHas('characters', [
            'slug'=>'finn'
        ]);

        
        $this->assertDatabaseHas('kingdoms', [
            'slug'=>'candy-kingdom'
        ]);

        
        $this->assertDatabaseHas('episodes', [
            'slug'=>'slumber-party-panic'
        ]);
    }


}
