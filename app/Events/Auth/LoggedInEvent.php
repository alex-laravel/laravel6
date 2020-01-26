<?php

namespace App\Events\Auth;

use App\Models\User\User;

class LoggedInEvent
{
    /**
     * @var User
     */
    public $user;

    /**
     * LoggedInEvent constructor.
     *
     * @param User $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
