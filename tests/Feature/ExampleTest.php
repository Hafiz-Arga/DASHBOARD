<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_forwarded_https_is_used_for_login_urls(): void
    {
        $response = $this
            ->withServerVariables([
                'HTTP_X_FORWARDED_PROTO' => 'https',
                'HTTP_X_FORWARDED_HOST' => 'superstore.test',
            ])
            ->get('/login');

        $response
            ->assertOk()
            ->assertSee('action="https://superstore.test/login"', false);
    }
}
