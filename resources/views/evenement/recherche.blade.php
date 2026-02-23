@extends('layouts.app')

@section('title', 'Recherche')
@section('content')
<div class="search-container">
    <form action="{{ route('recherche') }}" method="GET">
        <div class="search-bar">
            <input name="search" type="text" placeholder="Rechercher événements, lieux, profils..." value="{{ $search }}">
            <button type="submit" class="search-icon">🔍</button>
        </div>
    </form>
</div>
<div class="create-event">
    <a class="lien-discret" href="{{ route('creer') }}"><button>Créer un événement</button></a>
</div>
<h2 class="text-center">Événements</h2>
<div class="events-container">
    @if ($evenements->isEmpty())
        <p class="text-center">Aucun événement trouvé.@if($search) Essayez d'autres mots-clés. @endif</p>
    @else
        @foreach ($evenements as $evenement)
        <a class="lien-discret" href="{{ route('evenement', $evenement->id) }}">
            <div class="event-card">
                @if($evenement->image ?? null)
                    <img src="{{ asset('images/evenements/' . $evenement->image) }}" alt="{{ $evenement->nom }}" class="event-card-image">
                @else
                    <div class="event-card-image event-card-image-placeholder" aria-hidden="true"></div>
                @endif
                <h3>{{ $evenement->nom }} @if($evenement->diffusion_id == 2)<i class="fa-solid fa-lock" title="Privé"></i>@elseif($evenement->diffusion_id == 3)<i class="fa-solid fa-street-view" title="Communautaire"></i>@endif</h3>
                @if(isset($evenement->categories) && $evenement->categories->isNotEmpty())
                    <p class="event-card-categories">🏷️ @foreach($evenement->categories as $c)<span class="categorie-tag">{{ $c->libelle }}</span>@if(!$loop->last) @endif @endforeach</p>
                @endif
                <p>📍 {{ $evenement->ville ?? $evenement->lieu }} @if($evenement->code_postal ?? null) {{ $evenement->code_postal }} @endif</p>
                <p>📅 {{ $evenement->date_formatted ?? $evenement->date?->locale('fr')->isoFormat('D MMMM YYYY') }}</p>
                <p>🕒 {{ $evenement->heure }}</p>
            </div>
        </a>
        @endforeach
    @endif
</div>
<h2 class="text-center">Profils</h2>
<div class="profiles-container">
    @if ($users->isEmpty())
        <p class="text-center">Aucun profil trouvé.</p>
    @else
        @foreach ($users as $profil)
        <a class="lien-discret" href="{{ route('profil', $profil->id) }}">
            <div class="profile-card">
                <img src="{{ asset('images/profils/' . ($profil->image ?? 'profile.png')) }}" alt="Profil" width="100" height="100">
                <h3>{{ $profil->name }}</h3>
            </div>
        </a>
        @endforeach
    @endif
</div>
@endsection
