@extends('layouts.app')

@section('title', 'Modifier — ' . $evenement->nom)

@section('content')
<h1>Modifier l'événement</h1>
@if ($errors->any())
    <ul>@foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
@endif
<form method="POST" action="{{ url("/evenement/{$evenement->id}/modifier") }}">
    @csrf
    <div><label>Nom</label><input type="text" name="nom" value="{{ old('nom', $evenement->nom) }}" required></div>
    <div><label>Description</label><textarea name="description" required>{{ old('description', $evenement->description) }}</textarea></div>
    <div><label>Date</label><input type="date" name="date" value="{{ old('date', $evenement->date->format('Y-m-d')) }}" required></div>
    <div><label>Heure</label><input type="time" name="heure" value="{{ old('heure', $evenement->heure) }}" required></div>
    <div><label>Lieu</label><input type="text" name="lieu" value="{{ old('lieu', $evenement->lieu) }}" required></div>
    <div><label>Places max</label><input type="number" name="max_participants" value="{{ old('max_participants', $evenement->max_participants) }}" min="1"></div>
    <button type="submit">Enregistrer</button>
</form>
<p><a href="{{ url("/evenement/{$evenement->id}") }}">Retour</a></p>
@endsection
