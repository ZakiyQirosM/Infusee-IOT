<?php

if (!function_exists('checkLogin')) {
    function checkLogin()
    {
        if (!session()->has('pegawai')) {
            return redirect('/infusee')->with('error', 'Silakan login terlebih dahulu');
        }
    }
}
