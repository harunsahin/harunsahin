<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\CreatesApplication;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Test ortamı için varsayılan ayarlar
        $this->withoutVite();
        $this->withoutExceptionHandling();
        
        // Faker'ı Türkçe'ye ayarla
        $this->faker = \Faker\Factory::create('tr_TR');
    }

    protected function loginAsAdmin()
    {
        $admin = \App\Models\User::factory()->create([
            'role' => 'admin'
        ]);
        
        return $this->actingAs($admin);
    }
}
