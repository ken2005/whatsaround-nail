@extends('layouts.app')

@section('title', 'Mes inscriptions')

@section('content')
<h1>Mes inscriptions</h1>
@if($evenements->isEmpty())
    <p>Aucune inscription.</p>
@else
    <ul>
        @foreach($evenements as $e)
            <li><a href="{{ url('/evenement/'.$e->id) }}">{{ $e->nom }}</a> — {{ $e->date->format('d/m/Y') }} — {{ $e->lieu }}</li>
        @endforeach
    </ul>
@endif
<p><a href="{{ url('/') }}">Accueil</a></p>
@endsection
