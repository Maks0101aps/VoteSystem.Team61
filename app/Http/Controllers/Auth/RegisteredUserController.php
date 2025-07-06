<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\User;
use App\Mail\VerificationCode;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:25'],
            'last_name' => ['required', 'string', 'max:25'],
            'middle_name' => ['nullable', 'string', 'max:25'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:student,parent,teacher,director'],
            'school' => ['nullable', 'string', 'max:255'],
            'class_number' => ['nullable', 'integer'],
            'class_letter' => ['nullable', 'string', 'max:1'],
            'region' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
        ]);

        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->middle_name = $request->middle_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;
        $user->school = $request->school;
        $user->class_letter = $request->class_letter;
        $user->region = $request->region;
        $user->city = $request->city;
        $user->district = $request->district;
        $user->save();

        if ($request->class_number && $request->class_letter) {
            $schoolClass = SchoolClass::firstOrCreate(
                ['class_number' => $request->class_number, 'class_letter' => $request->class_letter]
            );
            $user->school_class_id = $schoolClass->id;
            $user->save();
        }

        Auth::login($user);

        // Generate and send verification code
        $code = $user->generateVerificationCode();
        Mail::to($user->email)->send(new VerificationCode($user, $code));

        return redirect()->route('verification.show');
    }
}
