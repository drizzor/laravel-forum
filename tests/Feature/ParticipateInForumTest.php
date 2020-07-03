<?php

namespace Tests\Feature;

use Illuminate\Fondation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ParticipateInForumTest extends TestCase
{
    // public function unauth_users_may_not_add_replies()
    // {
    //     $this->expectException('Illuminate\Auth\AutenticationException');
    //     $user = factory('App\User')->create();

    //     $reply = factory('App\Reply')->create();
    //     $this->post('threads/' . $thread->id . '/replies', $reply->toArray());
    // }

    /** @test */
    public function a_auth_user_may_participate_in_forum_threads()
    {
       $user = factory('App\User')->create();
       $this->be($user); // Login user

       $thread = factory('App\Thread')->create();

       $reply = factory('App\Reply')->make();
       $this->post('threads/' . $thread->id . '/replies', $reply->toArray());

       $this->get('/threads/' .$thread->id)
            ->assertSee($reply->body);

        // $response = $this->get('/threads');
        // $response->assertStatus(200);
    }
}
