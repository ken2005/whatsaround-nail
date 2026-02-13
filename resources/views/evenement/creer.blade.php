@extends('layouts.app')

@section('title', 'Créer un événement')

@section('content')
<h1>Créer un événement</h1>
@if ($errors->any())
    <ul>@foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
@endif
<form method="POST" action="{{ url('/docreer') }}">
    @csrf
    <div><label>Nom</label><input type="text" name="nom" value="{{ old('nom') }}" required></div>
    <div><label>Description</label><textarea name="description" required>{{ old('description') }}</textarea></div>
    <div><label>Date</label><input type="date" name="date" value="{{ old('date') }}" required></div>
    <div><label>Heure</label><input type="time" name="heure" value="{{ old('heure') }}" required></div>
    <div><label>Lieu</label><input type="text" name="lieu" value="{{ old('lieu') }}" required></div>
    <div><label>Places max (optionnel)</label><input type="number" name="max_participants" value="{{ old('max_participants') }}" min="1"></div>
    <button type="submit">Créer</button>
</form>
<p><a href="{{ url('/') }}">Retour</a></p>
@endsection
