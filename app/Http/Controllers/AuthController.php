<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // Tampilkan Form Register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses Register
    public function processRegister(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase() // Harus ada Huruf Besar & Kecil
                    ->numbers()   // Harus ada Angka
                    ->symbols()   // Harus ada Simbol (@$!%*?&)
                    ->uncompromised() // Cek apakah password pernah bocor di internet
            ],
        ], [
            // Custom Error Messages
            'name.required' => 'Nama wajib diisi!',
            'email.required' => 'Email jangan kosong.',
            'email.email' => 'Format email salah, cek lagi.',
            'email.unique' => 'Email ini udah dipake orang lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // 2. Simpan User ke Database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash password biar aman
        ]);

        // 3. Redirect ke Halaman Login dengan Pesan Sukses
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // Tampilkan Form Login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses Login
    public function processLogin(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Coba Login (Auth Attempt)
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/'); // Redirect ke Dashboard
        }

        // 3. Kalau Gagal, Balikin ke Login dengan Error
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput(); // Keep input email biar user gak ngetik ulang
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}