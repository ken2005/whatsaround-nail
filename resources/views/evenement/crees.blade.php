@extends('layouts.app')

@section('title', 'Mes événements créés')

@section('content')
<h1>Mes événements créés</h1>
@if($evenements->isEmpty())
    <p>Aucun événement créé.</p>
@else
    <ul>
        @foreach($evenements as $e)
            <li><a href="{{ url('/evenement/'.$e->id) }}">{{ $e->nom }}</a> — {{ $e->date->format('d/m/Y') }} — {{ $e->lieu }} — <a href="{{ url("/evenement/{$e->id}/modifier") }}">Modifier</a> — <a href="{{ url("/evenement/{$e->id}/participants") }}">Participants</a></li>
        @endforeach
    </ul>
@endif
<p><a href="{{ url('/') }}">Accueil</a></p>
@endsection
