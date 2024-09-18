<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController
{
    // Register page
    public function registerPage(){
        return view('register');
    }

    // Register User
    public function register(Request $request){
        try{
            $data = $request->validate([
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required|confirmed'
            ]);
    
            $user = User::create($data);
    
            return redirect()->route('LoginPage');
        }
        catch(Exception $e){
            abort(403, 'Something Went Wrong!');
        }
        
    }

    // login page
    public function loginPage(){
        return view('login');
    }

    // Login
    public function login(Request $request){
        $user = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(Auth::attempt($user)){
            return redirect()->route('home');
        }
        else{
            abort(403, 'Invalid Username or password');
        }
    }

    // Logout
    public function logout(){
        Auth::logout();

        return redirect()->route('LoginPage');
    }
}
