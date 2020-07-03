<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function guest_may_not_create_a_thread ()
    {
        // We should expect an authenticated error exception
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->withoutExceptionHandling();

        // Given we have a thread
        $thread = factory('App\Thread')->make();

        // And a guest posts a new thread to the endpoint
        $this->post('/threads', $thread->toArray());
    }


    /** @test */
    public function an_auth_user_can_create_new_forum_threads()
    {
        $this->withoutExceptionHandling();

        $user = factory('App\User')->create();

        $this->actingAs($user);

        $thread = factory('App\Thread')->create([
            'user_id' => $user->id
        ]);

        $this->post('/threads', $thread->toArray());

        $response = $this->get('threads/' . $thread->id);
            
        $response->assertSee($thread->title)
            ->assertSee($thread->body);
        // $response = $this->get('/threads');
        // $response->assertStatus(200);
    }
}
