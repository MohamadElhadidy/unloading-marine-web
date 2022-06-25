<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{

    public function __construct()
    {
        //$this->middleware('guest');
    }

    public function index()
    {
    return  view('auth.register');
    }
    
    protected function store(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|unique:users|max:255',
            'email' => 'required|unique:users|email|max:255',
            'username' => 'required|unique:users|max:255',
            'hint' => 'required',
            'password' => 'required',
        ]);
            
            
        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'hint' => $fields['hint'],
            'type' => $request->type,
            'username' => $fields['username'],
            'password' => Hash::make($request->password),
        ]);

        $response = [
            'user' => $user,
        ];

        return response($response, 201);
    }
}