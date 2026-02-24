@extends('layouts.app')

@section('title', 'Mes événements créés')

@section('content')
<h1 style="text-align: center;" class="mb-4">Mes événements</h1>
@if($evenements->isEmpty())
    <p class="text-center no-following mb-4">Vous n'avez pas encore créé d'événement.</p>
@else
    <div class="events-container">
        @foreach($evenements as $evenement)
            <a class="lien-discret" href="{{ url('/evenement/'.$evenement->id) }}">
                <div class="event-card">
                    <h3>{{ $evenement->nom }} @if($evenement->diffusion_id == 2)<i class="fa-solid fa-lock" title="Privé"></i>@elseif($evenement->diffusion_id == 3)<i class="fa-solid fa-street-view" title="Communautaire"></i>@endif</h3>
                    <p>📍 {{ $evenement->lieu }}</p>
                    <p>📅 {{ $evenement->date->format('d/m/Y') }}</p>
                    <p>🕒 {{ $evenement->heure }}</p>
                </div>
            </a>
        @endforeach
    </div>
@endif
@endsection
