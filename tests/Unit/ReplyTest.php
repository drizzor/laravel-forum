<?php

namespace Tests\Unit;

use Tests\TestCase;
// use Illuminate\Foundation\Testing\DatabaseMigrations;
// use Illuminate\Foundation\Testing\DatabaseTransactions;
// use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;

class ReplyTest extends TestCase
{
    /** @test */
    public function it_has_an_owner()
    {
       $reply = factory('App\Reply')->create();

       $this->assertInstanceOf('App\User', $reply->owner);
    }
}
