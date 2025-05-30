@extends('layouts.landing')

@section('title', 'Welcome to INFUSEE')

@section('content')
<div class="landing-container">
    <h1 class="landing-title">Welcome to INFUSEE</h1>
    <div class="landing-buttons">
        <a href="{{ route('login') }}" class="glass-button">
            <div class="icon"><box-icon name='user' color="#ffffff"></box-icon></div>
            <div class="text-btn">Login</div>
        </a>
        <a href="{{ route('infusee.index') }}" class="glass-button">
            <!--<div class="icon"><box-icon type='solid' name='dashboard' color="#ffffff"></box-icon></div>-->
            <svg xmlns="http://www.w3.org/2000/svg" height="100px" viewBox="0 -960 960 960" width="100px" fill="#FFFFFF"><path d="M132-160q-24 0-42-18t-18-42v-520q0-24 18-42t42-18h696q24 0 42 18t18 42H132v520h348v60H132Zm0-60v-520 520ZM750-80q-24.75 0-42.37-17.63Q690-115.25 690-140v-62q-73-11-121.5-66.5T520-400v-220q0-24.75 17.25-42.38Q554.5-680 580-680h280q24.75 0 42.38 17.62Q920-644.75 920-620v220q0 76-49 131.5T750-202v62h130v60H750Zm40-350h75v-195H575v115h55q30.49 0 57.74 14Q715-482 734-458q10 14 24.5 21t31.5 7Zm-70 175q53 0 92-32.5t50-82.5h-72q-30.85 0-58.42-13.5Q704-397 686-422q-10.59-12.83-25.29-20.42Q646-450 630-450h-55v50q0 61 42 103t103 42Zm-34-203Zm-446-82h220v-60H240v60Zm0 180h220v-60H240v60Z"/></svg>
            <div class="text-btn">Monitoring Infusee</div>
        </a>    
    </div>
</div>
@endsection
