<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('pages.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // dd($credentials);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Check if user has a role
        if (!$user->role) {
            throw ValidationException::withMessages([
                'email' => ['Your account does not have a role assigned.'],
            ]);
        }

        Auth::login($user);

        $request->session()->regenerate();

        // Redirect based on role
        return $this->redirectToRole($user);
    }

    /**
     * Redirect user based on their role
     */
    protected function redirectToRole(User $user): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
    {
        $roleName = $user->role->name;

        return match ($roleName) {
            'admin' => redirect('/admin/dashboard'),
            'college' => redirect('/college/dashboard'),
            'student' => redirect('/dashboard'),
            default => redirect('/'),
        };
    }

    /**
     * Show registration form
     */
    public function showRegistrationForm($role = null)
    {
        $roles = \App\Models\Role::all();
        return view('auth.register', compact('roles', 'role'));
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
        ]);

        Auth::login($user);

        return $this->redirectToRole($user);
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
