@extends('layouts.landing')

@section('title', 'Welcome to INFUSEE')

@section('content')
<div class="landing-container">
    <h1 class="landing-title">Welcome to INFUSEE</h1>
    <div class="landing-buttons">
        <a href="{{ route('register.index') }}" class="glass-button">
            <div class="icon"><box-icon name='user' color="#ffffff"></box-icon></div>
            <div class="text-btn">Login</div>
        </a>
        <a href="{{ route('infusee.index') }}" class="glass-button">
            <div class="icon"><box-icon type='solid' name='dashboard' color="#ffffff"></box-icon></div>
            <div class="text-btn">Monitoring Infusee</div>
        </a>    
    </div>
</div>
@endsection
