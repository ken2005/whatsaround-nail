@extends('layouts.app')

@section('title', $evenement->nom)

@section('content')
<h1>{{ $evenement->nom }}</h1>
<p><strong>Date :</strong> {{ $evenement->date->format('d/m/Y') }} {{ $evenement->heure }}</p>
<p><strong>Lieu :</strong> {{ $evenement->lieu }}</p>
<p><strong>Créateur :</strong> {{ $evenement->user->name }}</p>
<p>{{ $evenement->description }}</p>
@if($evenement->max_participants !== null)
    <p>Places : {{ $evenement->inscriptions_count }} / {{ $evenement->max_participants }}</p>
@else
    <p>{{ $evenement->inscriptions_count }} participants (places illimitées)</p>
@endif
@auth
    @if($inscrit)
        <form method="POST" action="{{ url("/evenement/{$evenement->id}/sedesinscrire") }}" style="display:inline">@csrf<button type="submit">Se désinscrire</button></form>
    @else
        @if($evenement->max_participants === null || $evenement->inscriptions_count < $evenement->max_participants)
            <form method="POST" action="{{ url("/evenement/{$evenement->id}/sinscrire") }}" style="display:inline">@csrf<button type="submit">S'inscrire</button></form>
        @endif
    @endif
    @if($evenement->user_id === auth()->id())
        <a href="{{ url("/evenement/{$evenement->id}/modifier") }}">Modifier</a>
        <form method="POST" action="{{ url("/evenement/{$evenement->id}") }}" style="display:inline" onsubmit="return confirm('Supprimer ?');">@csrf @method('DELETE')<button type="submit">Supprimer</button></form>
        <a href="{{ url("/evenement/{$evenement->id}/participants") }}">Participants</a>
    @endif
@endauth
@if(session('message'))<p>{{ session('message') }}</p>@endif
@if(session('error'))<p>{{ session('error') }}</p>@endif
<p><a href="{{ url('/') }}">Retour</a></p>
@endsection
