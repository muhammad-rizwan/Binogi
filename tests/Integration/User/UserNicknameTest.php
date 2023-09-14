<?php


namespace Tests\Integration\User;

use App\Models\User\User;
use App\Repositories\UserRepository;
use Tests\FrameworkTest;

class UserNicknameTest extends FrameworkTest
{

    /** @var UserRepository */
    private $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = app(UserRepository::class);
    }

    /**
     * Test validation for the length of the 'nickname' field when creating a user.
     *
     * @return void
     */
    public function testNicknameLengthValidationOnCreate(): void
    {
        // Attempt to create a user with a 'nickname' that is too short
        $responseShortNickname = $this->postJson('/api/users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'nickname' => '', // Too short (less than the minimum length)
        ]);

        // Assert that the response status is HTTP 422 (Unprocessable Entity)
        $responseShortNickname->assertStatus(422);

        // Assert that the response JSON contains a validation error for the 'nickname' field
        $responseShortNickname->assertJsonValidationErrors('nickname');

        // Attempt to create a user with a 'nickname' that is too long
        $responseLongNickname = $this->postJson('/api/users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'nickname' => str_repeat('a', 31), // Too long (exceeds the maximum length)
        ]);

        // Assert that the response JSON contains a validation error for the 'nickname' field
        $responseLongNickname->assertJsonValidationErrors('nickname');

        // Attempt to create a user with a 'nickname' of valid length
        $responseValidNickname = $this->postJson('/api/users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'nickname' => 'valid_nickname', // Valid length
        ]);

        // Assert that the response JSON contains the 'nickname' attribute
        $responseValidNickname->assertJson(['nickname' => 'valid_nickname']);

        // Assert that a user with the provided data exists in the database
        $this->assertDatabaseHas('users', ['nickname' => 'valid_nickname']);
    }

    /**
     * Test validation for the uniqueness of the 'nickname' field when creating a user.
     *
     * @return void
     */
    public function testNicknameUniquenessValidationOnCreate(): void
    {
        // Create a user with a 'nickname'
        $this->userFactory->create(['nickname' => 'existing_nickname']);

        // Attempt to create a user with the same 'nickname' (not unique)
        $response = $this->postJson('/api/users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'nickname' => 'existing_nickname', // Not unique (already exists)
        ]);

        // Assert that the response JSON contains a validation error for the 'nickname' field
        $response->assertJsonValidationErrors('nickname');
    }

    /**
     * Test validation for the uniqueness of the 'nickname' field when updating a user.
     *
     * @return void
     */
    public function testNicknameUniquenessValidationOnUpdate(): void
    {
        // Create two users with different 'nickname' values
        $this->userFactory->create(['nickname' => 'nickname1']);
        $this->userFactory->create(['nickname' => 'nickname2']);

        // Attempt to update the first user's 'nickname' to 'nickname2' (not unique)
        $userToUpdate = $this->repository->getModel()->newQuery()->where('nickname', 'nickname1')->first();
        $response = $this->putJson("/api/users/{$userToUpdate->id}", ['nickname' => 'nickname2']);

        // Assert that the response JSON contains a validation error for the 'nickname' field
        $response->assertJsonValidationErrors('nickname');
    }

    /**
     * Test validation for the uniqueness of the 'nickname' field when updating a user with the same value.
     *
     * @return void
     */
    public function testNicknameUniquenessValidationOnUpdateSameValue(): void
    {
        // Create a user with a 'nickname'
        $userToUpdate = $this->userFactory->create(['nickname' => 'unique_nickname']);

        // Attempt to update the user's 'nickname' to the same value (should pass as it's the same value)
        $response = $this->putJson("/api/users/{$userToUpdate->id}", ['nickname' => 'unique_nickname']);

        // Assert that the response status is HTTP 200 (OK)
        $response->assertStatus(200);
    }
}
