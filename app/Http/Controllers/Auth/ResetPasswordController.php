<?php

namespace App\Http\Controllers\Auth;

use App\Events\Auth\PasswordResetEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Repositories\Auth\UserRepository;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * ResetPasswordController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->middleware('guest');

        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @param string $token
     * @return View
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with([
            'email' => $request->email,
            'token' => $token
        ]);
    }

    /**
     * @param ResetPasswordRequest $request
     * @return RedirectResponse
     */
    public function reset(ResetPasswordRequest $request)
    {
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        return $response == Password::PASSWORD_RESET
            ? $this->sendResetResponse($response)
            : $this->sendResetFailedResponse($request, $response);
    }

    /**
     * @param CanResetPassword $user
     * @param string $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user = $this->userRepository->resetPassword($user, $password);

        event(new PasswordResetEvent($user));

        $this->guard()->login($user);
    }

    /**
     * @param string $response
     * @return RedirectResponse
     */
    protected function sendResetResponse($response)
    {
        return redirect($this->redirectTo())->withFlashSuccess(trans($response));
    }

    /**
     * @param Request $request
     * @param string $response
     * @return RedirectResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => trans($response)]);
    }

    /**
     * @return string
     */
    protected function redirectTo()
    {
        return homeRoute();
    }
}
