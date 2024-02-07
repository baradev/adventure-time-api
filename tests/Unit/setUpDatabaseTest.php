<?php

namespace Tests\Unit;

use Tests\TestCase;

class setUpDatabaseTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        // reset database and seed
        
        $this->artisan('migrate:fresh --seed');
        $this->assertTrue(true);

        // stop all tests
        $this->markTestIncomplete('something wen wrong seeding, check logs and your data added');
    }
}
