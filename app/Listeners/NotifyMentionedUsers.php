<?php

namespace App\Listeners;

use App\User;
use App\Events\ThreadReceivedNewReply;
use App\Notifications\YouWereMentioned;

class NotifyMentionedUsers
{
    /**
     * Handle the event.
     *
     * @param  ThreadReceivedNewReply  $event
     * @return void
     */
    public function handle(ThreadReceivedNewReply $event)
    {
        $mentionedUsers =  $event->reply->mentionedUsers();

        foreach ($mentionedUsers as $name) {
            if ($user = User::where('name', $name)->first()) {
                $user->notify(new YouWereMentioned($event->reply));
            }
        }

        // Approche Laravel - Pas trop fan? Compliqué? je préfère rester avec mon foreach()
        // User::whereIn('name', $event->reply->mentionedUsers())
        //     ->get() // -> va créer une collection de tous les utilisateurs
        //     ->each(function ($user) use ($event) {
        //         $user->notify(new YouWereMentioned($event->reply));
        //     });
    }
}
