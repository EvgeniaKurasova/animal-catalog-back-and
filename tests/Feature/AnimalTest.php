<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Animal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class AnimalTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Створюємо адміністратора
        $this->user = User::factory()->create([
            'name' => 'Admin',
            'lastname' => 'User',
            'phone_number' => '+380501234567',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'isAdmin' => true
        ]);
        
        // Отримуємо токен для автентифікації
        $response = $this->postJson('/api/auth/login', [
            'email' => $this->user->email,
            'password' => 'password'
        ]);
        
        $this->token = $response->json('token');
    }

    /** @test */
    public function it_can_create_animal()
    {
        $animalData = [
            'name' => 'Test Animal',
            'name_en' => 'Test Animal EN',
            'type' => 'dog',
            'type_en' => 'dog',
            'gender' => true,
            'age_years' => 2,
            'age_months' => 6,
            'size' => 'середній',
            'size_en' => 'medium',
            'additional_information' => 'Test description',
            'additional_information_en' => 'Test description EN',
            'is_sterilized' => true,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/animals', $animalData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'animalID',
                    'name',
                    'name_en',
                    'type',
                    'type_en',
                    'gender',
                    'age_years',
                    'age_months',
                    'size',
                    'size_en',
                    'is_sterilized',
                    'additional_information',
                    'additional_information_en',
                    'created_at'
                ]);

        $this->assertDatabaseHas('animals', [
            'name' => 'Test Animal',
            'type' => 'dog',
            'gender' => true
        ]);
    }
} 