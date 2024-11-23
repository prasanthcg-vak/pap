<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\AttemptLoginTrait;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers, AttemptLoginTrait {
        AttemptLoginTrait::credentials insteadof AuthenticatesUsers;
        AttemptLoginTrait::attemptLogin insteadof AuthenticatesUsers;
    }

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        // Check if the user exists and is active
        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user && $user->is_active == 0) {
            return $this->sendInactiveUserResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Send response for inactive users.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function sendInactiveUserResponse(Request $request)
    {
        throw \Illuminate\Validation\ValidationException::withMessages([
            $this->username() => 'Your account is not active. Please contact support.',
        ]);
    }

    /**
     * Override sendFailedLoginResponse to handle invalid credentials.
     *
     * @param Request $request
     * @return void
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw \Illuminate\Validation\ValidationException::withMessages([
            $this->username() => 'These credentials do not match our records.',
        ]);
    }
}
