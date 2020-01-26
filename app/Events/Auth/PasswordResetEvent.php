<?php

namespace App\Events\Auth;

use App\Models\User\User;

class PasswordResetEvent
{
    /**
     * @var User
     */
    public $user;

    /**
     * PasswordResetEvent constructor.
     *
     * @param User $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
