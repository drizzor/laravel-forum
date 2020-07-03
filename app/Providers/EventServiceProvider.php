<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // Event Laravel permettant l'envoi d'un email lors de l'enregistrement d'un user
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        // Event lors de l"ajout d'une nouvelle réponse
        'App\Events\ThreadReceivedNewReply' => [ // Quand tel event est appelé 
            'App\Listeners\NotifyMentionedUsers', // Faire ceci
            'App\Listeners\NotifySubscribers'
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
