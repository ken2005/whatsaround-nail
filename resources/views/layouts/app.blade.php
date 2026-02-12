<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <style>body{font-family:sans-serif;margin:0;padding:1rem;} nav{margin-bottom:1.5rem;} nav a{margin-right:1rem;}</style>
</head>
<body>
    <nav>
        <a href="{{ url('/') }}">Accueil</a>
        @auth
            <form action="{{ url('/logout') }}" method="POST" style="display:inline">@csrf<button type="submit">Déconnexion</button></form>
        @else
            <a href="{{ route('login') }}">Connexion</a>
            <a href="{{ route('register') }}">Inscription</a>
        @endauth
    </nav>
    <main>@yield('content')</main>
</body>
</html>
