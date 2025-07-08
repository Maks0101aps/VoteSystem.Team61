<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class LoginVerificationController extends Controller
{
    /**
     * Show the login verification form.
     */
    public function show(): Response|RedirectResponse
    {
        $userId = session()->get('login_user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $loginCode = null;
        if (config('app.debug')) {
            $user = User::find($userId);
            if ($user) {
                $latestCode = $user->loginCodes()->latest()->first();
                if ($latestCode) {
                    $loginCode = $latestCode->code;
                }
            }
        }

        return Inertia::render('Auth/VerifyLoginCode', [
            'loginCode' => $loginCode,
        ]);
    }

    /**
     * Verify the login code and log the user in.
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $userId = $request->session()->get('login_user_id');

        if (!$userId) {
            return redirect()->route('login')->withErrors(['code' => 'Your session has expired. Please try logging in again.']);
        }

        $user = User::find($userId);

        if (!$user || !$user->verifyLoginCode($request->code)) {
            return back()->withErrors(['code' => 'The provided code is invalid or has expired.']);
        }

        // Code is correct, log the user in
        Auth::login($user);

        // Clear the rate limiter
        $throttleKey = $request->session()->get('login_throttle_key');
        if ($throttleKey) {
            RateLimiter::clear($throttleKey);
            $request->session()->forget('login_throttle_key');
        }

        $request->session()->regenerate();

        $request->session()->forget('login_user_id');

        return redirect()->intended(route('dashboard.index'));
    }
}
