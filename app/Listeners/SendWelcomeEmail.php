<?php

namespace App\Listeners;

use App\Mail\WelcomeUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Illuminate\Auth\Events\Registered $event
     * @return void
     */
    public function handle(Registered $event)
    {
        Mail::to($event->user)->send(new WelcomeUser($event->user));
    }
}
