<?php

namespace App\Listeners\Auth;

use Illuminate\Events\Dispatcher;
use App\Events\Auth\LoggedInEvent;
use App\Events\Auth\LoggedOutEvent;
use App\Events\Auth\PasswordResetEvent;
use App\Events\Auth\RegisteredEvent;
use App\Events\Auth\VerifiedEvent;

class UserEventSubscriber
{
    /**
     * @param RegisteredEvent $event
     */
    public function onUserRegistered(RegisteredEvent $event)
    {
        \Log::info('User Registered: ' . $event->user->name);

        $event->user->sendEmailVerificationNotification();
    }

    /**
     * @param LoggedInEvent $event
     */
    public function onUserLoggedIn(LoggedInEvent $event)
    {
        \Log::info('User Logged In: ' . $event->user->name);
    }

    /**
     * @param LoggedOutEvent $event
     */
    public function onUserLoggedOut(LoggedOutEvent $event)
    {
        \Log::info('User Logged Out: ' . $event->user->name);
    }

    /**
     * @param PasswordResetEvent $event
     */
    public function onUserPasswordReset(PasswordResetEvent $event)
    {
        \Log::info('User Password Reset: ' . $event->user->name);
    }

    /**
     * @param VerifiedEvent $event
     */
    public function onUserEmailVerified(VerifiedEvent $event)
    {
        \Log::info('User Email Verified: ' . $event->user->name);
    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            RegisteredEvent::class,
            'App\Listeners\Auth\UserEventSubscriber@onUserRegistered'
        );

        $events->listen(
            LoggedInEvent::class,
            'App\Listeners\Auth\UserEventSubscriber@onUserLoggedIn'
        );

        $events->listen(
            LoggedOutEvent::class,
            'App\Listeners\Auth\UserEventSubscriber@onUserLoggedOut'
        );

        $events->listen(
            PasswordResetEvent::class,
            'App\Listeners\Auth\UserEventSubscriber@onUserPasswordReset'
        );

        $events->listen(
            VerifiedEvent::class,
            'App\Listeners\Auth\UserEventSubscriber@onUserEmailVerified'
        );
    }
}
