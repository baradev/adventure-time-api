<?php

namespace Tests\Feature;
 
use Database\Seeders\OrderStatusSeeder;
use Database\Seeders\TransactionStatusSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestCase;

class SetUpProjectTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function testSetUpProject()
    {
        
    }


}
