<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\VerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class VerificationController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('Auth/VerifyEmail');
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = Auth::user();

        if ($user->verifyCode($request->code)) {
            return redirect()->route('dashboard')->with('message', 'Email verified successfully!');
        }

        return back()->withErrors(['code' => 'Invalid or expired verification code.']);
    }

    public function resend(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user->email_verified_at) {
            return redirect()->route('dashboard');
        }

        // Delete old codes
        $user->verificationCodes()->delete();

        // Generate and send new code
        $code = $user->generateVerificationCode();
        Mail::to($user->email)->send(new VerificationCode($user, $code));

        return back()->with('message', 'A new verification code has been sent to your email.');
    }
}
