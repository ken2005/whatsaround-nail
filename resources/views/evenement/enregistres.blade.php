@extends('layouts.app')

@section('title', 'Mes enregistrements')
@section('content')
<h1 class="mb-4 text-center">Mes enregistrements</h1>
@if ($evenements->isEmpty())
    <p class="text-center no-following mb-4">Vous n'avez pas encore enregistré d'événement.</p>
@else
<div class="events-container">
    @foreach ($evenements as $evenement)
    <a class="lien-discret" href="{{ route('evenement', $evenement->id) }}">
        <div class="event-card">
            @if($evenement->image ?? null)
                <img src="{{ asset('images/evenements/' . $evenement->image) }}" alt="{{ $evenement->nom }}" class="event-card-image">
            @else
                <div class="event-card-image event-card-image-placeholder" aria-hidden="true"></div>
            @endif
            <h3>{{ $evenement->nom }} @if($evenement->diffusion_id == 2)<i class="fa-solid fa-lock" title="Privé"></i>@elseif($evenement->diffusion_id == 3)<i class="fa-solid fa-street-view" title="Communautaire"></i>@endif</h3>
            <p>📍 {{ $evenement->ville ?? $evenement->lieu }}</p>
            <p>📅 {{ $evenement->date }}</p>
            <p>🕒 {{ $evenement->heure }}</p>
        </div>
    </a>
    @endforeach
</div>
@endif
@endsection
