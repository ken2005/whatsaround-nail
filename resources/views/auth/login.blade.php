@extends('layouts.app')

@section('title', 'Connexion — ' . config('app.name'))

@section('content')
<div class="form-container">
    <h1 class="page-title">Connexion</h1>
    @if ($errors->any())
        <ul class="alert-error">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
    <form method="POST" action="{{ url('/login') }}">
        @csrf
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div class="form-group">
            <label><input type="checkbox" name="remember"> Se souvenir de moi</label>
        </div>
        <button type="submit">Se connecter</button>
    </form>
    <p style="margin-top:1rem"><a href="{{ route('register') }}">Créer un compte</a></p>
</div>
@endsection
