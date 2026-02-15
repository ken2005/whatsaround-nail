@extends('layouts.app')

@section('title', 'Profil — ' . $user->name)

@section('content')
<h1>{{ $user->name }}</h1>
<h2>Événements créés</h2>
@if($user->evenements->isEmpty())
    <p>Aucun événement créé.</p>
@else
    <ul>
        @foreach($user->evenements as $e)
            <li><a href="{{ url('/evenement/'.$e->id) }}">{{ $e->nom }}</a> — {{ $e->date->format('d/m/Y') }}</li>
        @endforeach
    </ul>
@endif
<p><a href="{{ url('/') }}">Accueil</a></p>
@endsection
