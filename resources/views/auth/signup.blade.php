@extends('layouts.app')

@section('title', 'Inscription — ' . config('app.name'))

@section('content')
<div class="form-container">
    <h1 class="page-title">Inscription</h1>
    @if ($errors->any())
        <ul class="alert-error">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
    <form method="POST" action="{{ url('/register') }}">
        @csrf
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required>
        </div>
        <button type="submit">S'inscrire</button>
    </form>
    <p style="margin-top:1rem"><a href="{{ route('connexion') }}">Déjà un compte ? Se connecter</a></p>
</div>
@endsection
