@extends('layouts.main')

@section('title', 'Register')

@section('content')

<div class="register-container">
    <h2 class="register-title">Registrasi Infusee</h2>

    <form action="{{ route('devices.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label class="register-label">No Register</label>
            <div class="register-input-container">
                <span>:</span>
                <input type="text" name="no_register" class="register-input" placeholder="Masukkan Nomor Registrasi Pasien">
            </div>
            <button type="button" class="register-btn-search">Cari</button>
        </div>

        <div class="form-group">
            <label class="register-label">Nama Pasien</label>
            <div class="register-input-container">
                <span>:</span>
                <input type="text" name="nama_pasien" class="register-input register-input-disabled" placeholder="Nama Pasien" disabled>
            </div>
        </div>

        <div class="form-group">
            <label class="register-label">Umur</label>
            <div class="register-input-container">
                <span>:</span>
                <input type="number" name="umur" class="register-input register-input-disabled" placeholder="Umur" disabled>
            </div>
        </div>

        <div class="form-group">
            <label class="register-label">No Ruangan</label>
            <div class="register-input-container">
                <span>:</span>
                <input type="text" name="no_ruangan" class="register-input register-input-disabled" placeholder="Nomor Ruangan" disabled>
            </div>
        </div>

        <div class="form-group">
            <label class="register-label">Durasi (menit)</label>
            <div class="register-input-container">
                <span>:</span>
                <input type="number" name="durasi" class="register-input" value="0">
            </div>
        </div>

        <button type="button" class="register-btn-submit">
            <a href="{{ route('devices.index') }}" style="text-decoration: none; color: inherit; display: block;">Cari Alat</a>
        </button>

    </form> 
</div>

<!-- Style di bagian bawah -->
<style>
    /* Container form */
    .register-container {
        background-color: #ffffff;
        padding: 24px;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        margin-top: 20px;
        width: 100%;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Judul */
    .register-title {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 16px;
        text-align: center;
        color: #333;
    }

    /* Group Form */
    .form-group {
        margin-bottom: 16px;
        display: flex;
        align-items: center;
    }

    /* Label form */
    .register-label {
        width: 25%;
        font-weight: 500;
        color: #333;
        padding-right: 10px;
    }

    /* Input container */
    .register-input-container {
        display: flex;
        align-items: center;
        width: 60%;
    }

    .register-input-container span {
        margin-right: 5px;
        font-weight: 500;
        color: #555;
    }

    /* Input */
    .register-input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        transition: border-color 0.2s ease;
        font-size: 16px;
        color: #333;
    }

    .register-input:focus {
        outline: none;
        border-color: #00C7B4;
        box-shadow: 0 0 5px rgba(0, 199, 180, 0.5);
    }

    /* Input disabled */
    .register-input-disabled {
        background-color: #f3f3f3;
        color: #999;
    }

    /* Tombol Cari */
    .register-btn-search {
        background-color: #00C7B4;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s ease;
        margin-left: 10px;
    }

    .register-btn-search:hover {
        background-color: #009688;
    }

    /* Tombol Submit */
    .register-btn-submit {
        background-color: #00C7B4;
        color: #fff;
        padding: 14px;
        width: 100%;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s ease;
        margin-top: 20px;
    }

    .register-btn-submit:hover {
        background-color: #009688;
    }
</style>

@endsection
