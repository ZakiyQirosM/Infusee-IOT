<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.index');
    }

    public function login(Request $request)
    {
        $pegawai = Pegawai::where('no_peg', $request->no_peg)->first();

        if ($pegawai && Hash::check($request->password, $pegawai->password)) {
            Auth::guard('pegawai')->login($pegawai);

            $pegawai->update([
                'last_login_at' => now(),
                'last_activity_at' => now(),
            ]);

            return redirect('/register');
        }

        return redirect()->back()->with('error', 'Login gagal');
    }

    public function logout()
    {
        Auth::guard('pegawai')->logout();
        return redirect('/')->with('success', 'Berhasil logout');
    }

}
