<?php

namespace Tests\Feature;

use App\Thread;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
// use Illuminate\Fondation\Testing\DatabaseMigrations;

class ReadThreadsTest extends TestCase
{
    public function setUp():void
    {
       parent::setUp();

       $this->thread = factory('App\Thread')->create();
    }

    /** @test */
    public function view_all_threads()
    {           
        $response = $this->get('/threads');
        $response->assertSee($this->thread->title);
    }

     /** @test */
     public function a_user_can_read_a_single_thread()
     {
        $response = $this->get('/threads/' . $this->thread->id);
        $response->assertSee($this->thread->title);
     }

     /** @test */
     public function a_user_can_read_replies_that_are_associated_with_a_thread()
     {
        $reply = factory('App\Reply')->create(['thread_id' => $this->thread->id]);

        $response = $this->get('/threads/' . $this->thread->id)
            ->assertSee($reply->body);
     }
}
