<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ImpersonationLog;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
       
    public function impersonate($userId)
    {
        $impersonator = Auth::user();

        // Ensure the impersonator is a super admin
        if (!$impersonator->isSuperAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Ensure the impersonated user is not another super admin
        $user = User::findOrFail($userId);
        if ($user->isSuperAdmin()) {
            abort(403, 'Cannot impersonate another super admin.');
        }

        // Log impersonation start
        ImpersonationLog::create([
            'impersonator_id' => $impersonator->id,
            'impersonated_user_id' => $userId,
            'started_at' => now(),
        ]);

        // Store impersonator's ID in session and login as the selected user
        session(['impersonator_id' => $impersonator->id]);
        Auth::login($user);

        return redirect('/')->with('success', "You are now logged in as {$user->name}");
    }

    public function stopImpersonation()
    {
        $impersonatorId = session('impersonator_id');

        if ($impersonatorId) {
            // Log impersonation end
            ImpersonationLog::where('impersonator_id', $impersonatorId)
                ->where('ended_at', null)
                ->update(['ended_at' => now()]);

            // Login as impersonator and clear session
            $impersonator = User::findOrFail($impersonatorId);
            Auth::login($impersonator);
            session()->forget('impersonator_id');

            return redirect('/users')->with('success', 'You have returned to your admin account.');
        }

        return redirect('/')->with('error', 'You are not impersonating anyone.');
    }

    public function viewLogs()
    {
        $logs = ImpersonationLog::with('impersonator', 'impersonatedUser')->latest()->paginate(10);

        return view('admin.impersonation-logs', compact('logs'));
    }
}
