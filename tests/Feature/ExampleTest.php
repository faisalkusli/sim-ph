<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        // Root redirects to login or home, so 302 is expected
        $this->assertTrue(in_array($response->getStatusCode(), [200, 302]));
    }
}