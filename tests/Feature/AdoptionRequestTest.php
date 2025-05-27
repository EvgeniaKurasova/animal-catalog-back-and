<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Animal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class AdoptionRequestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $animal;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Створюємо користувача
        $this->user = User::factory()->create([
            'name' => 'Test',
            'lastname' => 'User',
            'phone_number' => '+380501234569',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password'),
            'isAdmin' => false
        ]);
        
        // Створюємо тварину
        $this->animal = Animal::factory()->create();
        
        // Отримуємо токен для автентифікації
        $response = $this->postJson('/api/auth/login', [
            'email' => $this->user->email,
            'password' => 'password'
        ]);
        
        $this->token = $response->json('token');
    }

    /** @test */
    public function it_can_create_adoption_request()
    {
        $requestData = [
            'animalID' => $this->animal->animalID,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '+380501234567',
            'email' => 'john@example.com',
            'message' => 'I would like to adopt this animal',
            'city' => 'Kyiv',
            'address' => 'Main Street 1',
            'reason' => 'I love animals',
            'experience' => 'I have experience with pets',
            'living_conditions' => 'I live in a house with a garden',
            'other_pets' => 'I have a cat',
            'agreement' => true
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/adoption-requests', $requestData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'requestID',
                    'animalID',
                    'userID',
                    'first_name',
                    'last_name',
                    'phone',
                    'email',
                    'message',
                    'city',
                    'address',
                    'reason',
                    'experience',
                    'living_conditions',
                    'other_pets',
                    'is_processed',
                    'is_archived',
                    'comment',
                    'created_at'
                ]);

        $this->assertDatabaseHas('adoption_requests', [
            'animalID' => $this->animal->animalID,
            'userID' => $this->user->userID,
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]);
    }
} 