<?php

namespace App\Events\Auth;

use App\Models\User\User;

class VerifiedEvent
{
    /**
     * @var User
     */
    public $user;

    /**
     * VerifiedEvent constructor.
     *
     * @param User $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
