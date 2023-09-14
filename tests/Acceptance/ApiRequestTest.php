<?php

namespace Tests\Acceptance;

use Tests\FrameworkTest;

class ApiRequestTest extends FrameworkTest
{

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test  non existent API request.
     *
     * @return void
     */
    public function testNonExistentApiRequest(): void
    {
        // Attempt to create a user with a 'nickname' that is too short
        $apiResponse = $this->postJson('/api/hack', []);

        // Assert that the response status is HTTP 404
        $apiResponse->assertStatus(404);
    }
}
