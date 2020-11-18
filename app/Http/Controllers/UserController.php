<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{

    public function register(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|min:6',
            'password' => 'required|min:6'
        ]);

        $attributes = [
            'username' => $request->username,
            'password' => app('hash')->make($request->password)
        ];
        $user = User::create($attributes);
        return $user;
    }
}
