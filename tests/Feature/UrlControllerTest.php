<?php

namespace Tests\Feature;


use App\Models\Url;
use App\Models\User;
use Tests\TestCase;

class UrlControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_unauthenticated_user_adds_a_url()
    {
        $user = User::factory()->create();
        $url = 'https://www.pexels.com/photo/macbook-pro-on-brown-wooden-table-5860964/';
        $this
            ->postJson('api/urls/add-url', [
                'url' => $url,
            ])
            ->assertJson([
                'url' => $url,
            ]);
        $this
            ->assertDatabaseHas('urls',[
               'url' => $url
            ]);
    }


    public function test_authenticated_user_adds_a_url()
    {
        $url = 'https://www.pexels.com/photo/macbook-pro-on-brown-wooden-table-5860964/';
        $user = User::factory()->create();
        $this
            ->actingAs($user)
            ->postJson('api/urls/add-url', [
                'url' => $url,
            ])
            ->dump()
            ->assertJson([
                'url' => $url,
                'creator_id' => $user->id
            ]);
        $this
            ->assertDatabaseHas('urls',[
                'url' => $url,
                'creator_id' => $user->id
            ]);
    }

    public function test_user_can_not_delete_url(){
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $url = Url::factory()->create([
            'creator_id' => $userB->id
        ]);

        $this
            ->actingAs($userA)
            ->deleteJson("api/urls/$url->id")
            ->dump()
            ->assertJson([
               'message' => 'You are not allowed to delete this url.'
            ]);
        $this
            ->assertDatabaseHas('urls',[
                'id' => $url->id
            ]);
    }

}
