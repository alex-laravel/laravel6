<?php

namespace App\Http\Controllers\Auth;

use App\Events\Auth\LoggedInEvent;
use App\Events\Auth\LoggedOutEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * LoginController constructor.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * @return View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * @param LoginRequest $request
     * @return RedirectResponse
     */
    public function login(LoginRequest $request)
    {
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($user);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->authenticated($this->guard()->user());
    }

    /**
     * @param User $user
     * @return RedirectResponse
     */
    protected function authenticated($user)
    {
        event(new LoggedInEvent($user));

        return redirect()->intended($this->redirectTo());
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => trans('auth.failed')]);
    }

    /**
     * @param User $user
     * @return RedirectResponse
     */
    protected function loggedOut($user)
    {
        event(new LoggedOutEvent($user));

        return redirect($this->redirectTo());
    }

    /**
     * @return string
     */
    protected function redirectTo()
    {
        return homeRoute();
    }
}
