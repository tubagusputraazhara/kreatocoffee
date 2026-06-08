<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman form login
    public function showLoginForm()
    {
        return view('login');
    }

    // Memproses pengecekan akun saat tombol login ditekan
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Memeriksa kesesuaian data ke database phpMyAdmin
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Jika sukses, lempar kasir ke halaman dashboard kasir pos
            return redirect()->intended('/kasir');
        }

        // Jika gagal, kembalikan ke form login dengan pesan peringatan
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    // Memproses keluar sistem (logout)
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}