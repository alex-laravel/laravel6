<?php

namespace App\Http\Controllers\Auth;

use App\Events\Auth\RegisteredEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User\User;
use App\Repositories\Auth\UserRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * RegisterController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->middleware('guest');

        $this->userRepository = $userRepository;
    }

    /**
     * @return View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * @param RegisterRequest $request
     * @return RedirectResponse
     */
    public function register(RegisterRequest $request)
    {
        $user = $this->create($request->all());

        return $this->registered($user);
    }

    /**
     * @param User $user
     * @return RedirectResponse
     */
    protected function registered($user)
    {
        event(new RegisteredEvent($user));

        Auth::guard()->login($user);

        return redirect($this->redirectTo())->withFlashSuccess(trans('alerts.auth.verify.sent'));
    }

    /**
     * @param array $data
     * @return User
     */
    protected function create(array $data)
    {
        return $this->userRepository->create($data);
    }

    /**
     * @return string
     */
    protected function redirectTo()
    {
        return homeRoute();
    }
}
