<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            ]);

            return redirect('/register');
        }

        return redirect()->back()->with('error', 'Ups! NIP atau password salah');
    }

    public function logout(Request $request)
    {
        $pegawai = Auth::guard('pegawai')->user();

        if ($pegawai) {
            DB::table('table_pegawai')
                ->where('no_peg', $pegawai->no_peg)
                ->update(['last_activity_at' => now()]);
        }

        Auth::guard('pegawai')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Berhasil logout');
    }
}
