@extends('layouts.app')

@section('title', 'Participants — ' . $evenement->nom)

@section('content')
<h1>Participants — {{ $evenement->nom }}</h1>
<ul>
    @foreach($participants as $p)
        <li>{{ $p->name }} ({{ $p->email }})</li>
    @endforeach
</ul>
<p><a href="{{ url("/evenement/{$evenement->id}") }}">Retour</a></p>
@endsection
