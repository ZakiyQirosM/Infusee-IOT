<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    public function showResetForm()
    {
        return view('auth.reset_form');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'no_peg' => 'required'
        ]);

        $pegawai = Pegawai::where('no_peg', $request->no_peg)->first();

        if (!$pegawai) {
            return back()->withErrors(['no_peg' => 'NIK tidak ditemukan.']);
        }

        $url = url(route('password.set.form', ['nik' => $pegawai->no_peg]));
        $message = "Halo {$pegawai->nama_peg},\nKlik link berikut untuk atur ulang password Anda:\n$url";

        Http::withHeaders([
            'Authorization' => env('FONNTE_TOKEN'),
        ])->post('https://api.fonnte.com/send', [
            'target' => $pegawai->no_wa,
            'message' => $message,
        ]);

        return back()->with('success', 'Link reset password telah dikirim ke WhatsApp.');
    }

    public function showNewPasswordForm(Request $request)
    {
        $nik = $request->query('nik');
        $pegawai = Pegawai::where('no_peg', $nik)->first();

        if (!$pegawai) {
            return redirect()->route('login')->withErrors(['no_peg' => 'NIK tidak ditemukan.']);
        }

        return view('auth.reset_new_password', compact('pegawai'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'no_peg' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $pegawai = Pegawai::where('no_peg', $request->no_peg)->first();

        if (!$pegawai) {
            return redirect()->route('login')->withErrors(['no_peg' => 'NIK tidak ditemukan.']);
        }

        $pegawai->password = Hash::make($request->password);
        $pegawai->save();

        return redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login.');
    }
}
