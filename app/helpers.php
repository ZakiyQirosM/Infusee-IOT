<?php

if (!function_exists('checkLogin')) {
    function checkLogin()
    {
        if (!session()->has('pegawai')) {
            return redirect('/infusee')->with('error', 'Silakan login terlebih dahulu');
        }
    }
}

if (!function_exists('maskNama')) {
    function maskNama($nama) {
        $nama = substr($nama, 0, 9);
        $output = '';
        $length = strlen($nama);

        for ($i = 0; $i < $length; $i += 6) {
            $part = substr($nama, $i, 3);
            $mask = substr($nama, $i + 3, 3);
            $output .= $part;
            if ($mask) {
                $output .= str_repeat('*', strlen($mask));
            }
        }

        return $output;
    }
}
