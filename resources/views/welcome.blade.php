@extends('layouts.app')

@section('title', 'Accueil — ' . config('app.name'))

@section('content')
<div class="search-container">
    <form action="{{ url('/') }}" method="GET">
        <div class="search-bar">
            <input type="text" placeholder="Rechercher par lieu, nom, date..." name="search" value="{{ request('search') }}">
            <button type="submit" class="search-icon">🔍</button>
        </div>
    </form>
</div>

<div class="create-event">
    <a class="lien-discret" href="{{ url('/creer') }}">
        <button type="button">Créer un événement</button>
    </a>
</div>

@if(isset($evenements) && $evenements->isNotEmpty())
    <h2 class="text-center">Événements à venir</h2>
    <br>
    <div class="events-container">
        @foreach($evenements as $evenement)
            <a class="lien-discret" href="{{ url('/evenement/'.$evenement->id) }}">
                <div class="event-card">
                    @if($evenement->image ?? null)
                        <img src="{{ asset('images/evenements/' . $evenement->image) }}" alt="{{ $evenement->nom }}" class="event-card-image">
                    @else
                        <div class="event-card-image event-card-image-placeholder" aria-hidden="true"></div>
                    @endif
                    <h3>{{ $evenement->nom }} @if($evenement->diffusion_id == 2)<i class="fa-solid fa-lock" title="Privé"></i>@elseif($evenement->diffusion_id == 3)<i class="fa-solid fa-street-view" title="Communautaire"></i>@endif</h3>
                    @if($evenement->categories && $evenement->categories->isNotEmpty())
                        <p class="event-card-categories">🏷️ @foreach($evenement->categories as $c)<span class="categorie-tag">{{ $c->libelle }}</span>@if(!$loop->last) @endif @endforeach</p>
                    @endif
                    <p>📍 {{ $evenement->ville ?? $evenement->lieu }}</p>
                    <p>📅 {{ $evenement->date->format('d/m/Y') }}</p>
                    <p>🕒 {{ $evenement->heure }}</p>
                    @if($evenement->max_participants)
                        <p>({{ $evenement->inscriptions_count }} / {{ $evenement->max_participants }} places)</p>
                    @else
                        <p>({{ $evenement->inscriptions_count }} participants)</p>
                    @endif
                </div>
            </a>
        @endforeach
    </div>
    <br>
@else
    <p class="text-center">Aucun événement à venir.</p>
@endif
@endsection
