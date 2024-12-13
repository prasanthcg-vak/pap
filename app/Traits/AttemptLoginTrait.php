<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait AttemptLoginTrait
{
    /**
     * Attempt to log the user in with "Remember Me" functionality.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    // protected function attemptLogin(Request $request)
    // {
    //     return Auth::attempt(
    //         $this->credentials($request),
    //         $request->filled('remember') // Handles the "Remember Me" checkbox
    //     );
    // }

    public function attemptLogin(Request $request, $remember = false)
    {
        return Auth::attempt($this->credentials($request), $remember);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only('email', 'password');
    }
}
