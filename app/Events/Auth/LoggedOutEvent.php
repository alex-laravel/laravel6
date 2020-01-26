<?php

namespace App\Events\Auth;

use App\Models\User\User;

class LoggedOutEvent
{
    /**
     * @var User
     */
    public $user;

    /**
     * LoggedOutEvent constructor.
     *
     * @param User $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
