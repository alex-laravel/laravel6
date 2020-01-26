<?php

namespace App\Events\Auth;

use App\Models\User\User;

class RegisteredEvent
{
    /**
     * @var User
     */
    public $user;

    /**
     * RegisteredEvent constructor.
     *
     * @param User $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
