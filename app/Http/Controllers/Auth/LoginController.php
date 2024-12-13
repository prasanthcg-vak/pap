<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\AttemptLoginTrait;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\User;

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

    // public function login(Request $request)
    // {
    //     $this->validateLogin($request);

    //     // Initialize max attempts and session tracking
    //     $maxAttempts = 3;
    //     $attempts = session('login_attempts', 0);

    //     // Check if the user exists and is active
    //     $user = \App\Models\User::where('email', $request->email)->first();

    //     if ($user && $user->is_active == 0) {
    //         return $this->sendInactiveUserResponse($request);
    //     }

    //     // Lock the user out after exceeding max attempts
    //     if ($attempts >= $maxAttempts) {
    //         return back()->withErrors([
    //             'email' => 'Too many login attempts. Please contact admin.',
    //         ]);
    //     }

    //     // Attempt to log in
    //     if ($this->attemptLogin($request)) {
    //         // Reset login attempts on successful login
    //         session()->forget('login_attempts');
    //         return $this->sendLoginResponse($request);
    //     }

    //     // Increment login attempts on failure
    //     session(['login_attempts' => $attempts + 1]);

    //     // Check if this is the last attempt
    //     if ($attempts + 1 >= $maxAttempts) {
    //         return back()->withErrors([
    //             'email' => 'Too many login attempts. Please contact admin.',
    //         ]);
    //     }

    //     return $this->sendFailedLoginResponse($request);
    // }

    public function login(Request $request)
    {
        $this->validateLogin($request);
    
        // Retrieve user by email
        $user = \App\Models\User::where('email', $request->email)->first();
    
        // Check if the user exists
        if (!$user) {
            return $this->sendFailedLoginResponse($request);
        }
    
        if ($user && $user->is_active == 0) {
            return $this->sendInactiveUserResponse($request);
        }

        // Check if the user is blocked
        if ($user->is_blocked) {
            return back()->withErrors(['email' => 'Your account is blocked. Please contact the administrator.']);
        }
    
        // Check if login is successful
        // if ($this->attemptLogin($request)) {
        //     $user->update(['login_attempts' => 0]); // Reset login attempts on successful login
        //     return $this->sendLoginResponse($request);
        // }

        // Check if login is successful
        $remember = $request->has('remember'); // Check if "Remember Me" is checked
        if ($this->attemptLogin($request, $remember)) {
            $user->update(['login_attempts' => 0]); // Reset login attempts on successful login
            return $this->sendLoginResponse($request);
        }
    
        // Increment failed attempts
        $user->increment('login_attempts');
    
        // Block user if attempts reach 3
        if ($user->login_attempts >= 3) {
            $user->update(['is_blocked' => 1]);
            return back()->withErrors(['email' => 'Your account has been blocked after 3 failed attempts.Please contact the administrator']);
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
        $maxAttempts = 3; // Maximum allowed attempts
        $key = $this->throttleKey($request); // Throttle key for the user
       
    
        // Check if the user has exceeded their attempts
        
    
        // Check if user exists in the database
        $userExists = User::where($this->username(), $request->input($this->username()))->exists();
    
        if (!$userExists) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                $this->username() => "No user found for the given {$this->username()}.",
            ]);
        }
        $user = User::where('email', $request->email)->first();
        $attemptsLeft = $maxAttempts - $user->login_attempts;

        throw \Illuminate\Validation\ValidationException::withMessages([
            $this->username() => "These credentials do not match our records. You have {$attemptsLeft} attempts remaining.",
        ]);
    }
    

    // protected function authenticated(Request $request, $user)
    // {
    //     if (!$user->hasVerifiedEmail()) {
    //         auth()->logout();

    //         return redirect('/login')->withErrors([
    //             'email' => 'You need to verify your email address before logging in.',
    //         ]);
    //     }
    // }

    public function logout(Request $request)
    {
        Auth::logout();
    
        // Clear session and regenerate token
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        // Optionally clear remember me cookie
        $cookie = \Cookie::forget('remember_web_' . sha1(config('app.key')));
        return redirect('/login')->with('status', 'Logged out successfully!')->withCookie($cookie);
    }
    


}
