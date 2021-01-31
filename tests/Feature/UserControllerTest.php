<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_register()
    {
        $name = 'qwerty';
        $email = 'qwerty@mail.ru';
        $password = 'querty123';

        $this
            ->postJson('api/register', [
                'name' => $name,
                'email' => $email,
                'password' => \Hash::make($password)
            ])
            ->assertCreated()
            ->assertJson([
                'name' => $name,
                'email' => $email
            ]);

        $this
            ->assertDatabaseHas('users', [
                'name' => $name,
                'email' => $email
            ]);
    }

    public function test_user_fails_to_register()
    {
        $name = 'qwerty';
        $email = 'qwerty@mail.ru';
        $password = 'querty123';

        $this
            ->postJson('api/register', [

            ])
            ->assertJson([
                'errors' => [
                    'name' => ['The name field is required.'],
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                ]
            ]);
        $this
            ->assertDatabaseMissing('users', [
                'name' => $name,
                'email' => $email
            ]);
    }

    public function test_user_can_login()
    {
        $email = 'qwerty@mail.ru';
        $password = 'qwerty123';
        $user = User::factory()->create([
            'email' => $email,
            'password' => \Hash::make($password)
        ]);

        $result = $this
            ->actingAs($user)
            ->postJson('api/login', [
                'email' => $email,
                'password' => $password
            ])
            ->assertJson([
                'email' => $email
            ]);
        $this
            ->assertDatabaseHas('users', [
                'email' => $email,
            ]);
    }

    public function test_user_fails_to_login()
    {
        $name = 'qwerty';
        $email = 'qwerty@mail.ru';
        $password = 'qwerty123';
        $user = User::factory()->create([
            'name' => $name,
            'email' => $email,
            'password' => \Hash::make($password)
        ]);

        $this
            ->actingAs($user)
            ->postJson('api/login', [

            ])
            ->assertJson([
                'errors' => [
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.']
                ]
            ]);

    }

}
