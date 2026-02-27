<!DOCTYPE html>
<html lang="fr" id="auth-page">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>What's Around - Inscription</title>
    <link rel="stylesheet" href="{{ asset('app.css') }}">
</head>
<body>
    <header>
        <h1>What's Around</h1>
    </header>

    <div class="login-container">
        <h2 class="page-title">Créer un compte</h2>

        @if ($errors->any())
            <ul class="alert-error">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label for="name">Nom</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label for="email">Adresse e-mail</label>
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

            <div class="form-group">
                <button type="submit">S'inscrire</button>
            </div>

            <div class="login-link">
                Déjà un compte ? <a href="{{ route('connexion') }}">Se connecter</a>
            </div>
        </form>
    </div>
</body>
</html>
