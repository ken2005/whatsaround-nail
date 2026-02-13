@extends('layouts.app')

@section('title', 'Accueil — ' . config('app.name'))

@section('content')
<h1>Événements à venir</h1>
@if(isset($evenements) && $evenements->isNotEmpty())
    <ul class="events-list">
        @foreach($evenements as $e)
            <li>
                <a href="{{ url('/evenement/'.$e->id) }}">{{ $e->nom }}</a>
                — {{ $e->date->format('d/m/Y') }} {{ $e->heure }} — {{ $e->lieu }}
                @if($e->max_participants)
                    ({{ $e->inscriptions_count }} / {{ $e->max_participants }} places)
                @endif
            </li>
        @endforeach
    </ul>
@else
    <p>Aucun événement à venir.</p>
@endif
@endsection
