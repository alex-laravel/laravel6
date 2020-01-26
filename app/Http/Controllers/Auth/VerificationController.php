<?php

namespace App\Http\Controllers\Auth;

use App\Events\Auth\VerifiedEvent;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VerificationController extends Controller
{
    use VerifiesEmails;

    /**
     * VerificationController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function show(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectTo());
        }

        return view('auth.verify');
    }

    /**
     * @param Request $request
     * @return Response
     * @throws AuthorizationException
     */
    public function verify(Request $request)
    {
        $routeId = (string)$request->route('id');
        $routeHash = (string)$request->route('hash');
        $userId = (string)$request->user()->getKey();
        $userEmail = $request->user()->getEmailForVerification();

        if (!hash_equals($routeId, $userId)) {
            throw new AuthorizationException;
        }

        if (!hash_equals($routeHash, generateVerifyEmailHash($userId, $userEmail))) {
            throw new AuthorizationException;
        }

        if ($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectTo());
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new VerifiedEvent($request->user()));
        }

        return redirect($this->redirectTo());
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectTo());
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->withFlashSuccess(trans('alerts.auth.verify.sent'));
    }

    /**
     * @return string
     */
    protected function redirectTo()
    {
        return homeRoute();
    }
}
