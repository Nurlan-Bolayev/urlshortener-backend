<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $attrs = $request->validate([
            'name' => 'required|string|min:4',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:4'
        ], [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.unique' => 'This email already exists.',
            'password.min' => 'The password should be at least 4 characters long.',
        ]);

        $user = User::query()->forceCreate($attrs);
        \Auth::login($user);
        return $user;
    }

    public function login(Request $request)
    {
        $attrs = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:4'
        ], [
            'email.exists' => 'This email already exists.',
            'password.required' => 'The password field is required.'
        ]);

        if(\Auth::attempt($attrs)){
            return \Auth::user();
        }
        throw ValidationException::withMessages([
           'errors' => [
               'message' => ['These credentials do not match our records']
           ]
        ]);
    }
}
