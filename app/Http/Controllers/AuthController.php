<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Uncomment the following method if you have an 'auth/login' view
    // public function index()
    // {
    //     return view('auth.login');
    // }

    public function login(Request $request)
    {
        if(User::where('username', $request->username)->count() == 0){
            return back()->with('invalidlogin', 'Invalid username');
        }
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]); 
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }
        return back()->with('invalidlogin', 'Invalid username or password');

    }
    

    public function register(Request $request)
    {
        $nama = $request->input('nama');
        $username = $request->input('username');
        $password = $request->input('password');

       
            User::create([
                'nama' => $nama,
                'username' => $username,
                'password' => Hash::make($password),
            ]);
        

            return redirect()->intended('/login');
        
    }

    public function logout()
    {
        session(['role' => 0]);
        auth('web')->logout();

        return redirect('/login');
    }
}
