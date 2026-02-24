@extends('layouts.app')

@section('title', $evenement->nom)
@section('content')
<div id="consulter-evenement">
    <div class="event-details">
        <div class="event-info">
            @if($evenement->image)
                <div class="event-image-wrap">
                    <img src="{{ asset('images/evenements/' . $evenement->image) }}" alt="{{ $evenement->nom }}" class="event-detail-image">
                </div>
            @endif
            <h2>{{ $evenement->nom }}
            @if ($evenement->diffusion_id == 2)
            <i class="fa-solid fa-lock" title="Privé"></i>
            @elseif ($evenement->diffusion_id == 3)
            <i class="fa-solid fa-street-view" title="Communautaire"></i>
            @endif
            </h2>
            <p>📍 {{ $evenement->ville ? trim(($evenement->num_rue ?? '') . ' ' . ($evenement->allee ?? '') . ', ' . $evenement->ville . ' ' . ($evenement->code_postal ?? '') . ' ' . ($evenement->pays ?? '')) : $evenement->lieu }}</p>
            <p>📅 {{ $dateFormatted ?? $evenement->date->format('d/m/Y') }}</p>
            <p>🕒 {{ $evenement->heure }}</p>
            <p>👥 Organisateur: <a class="lien" href="{{ route('profil', $evenement->user->id) }}">{{ $evenement->user->name }}</a></p>
            @if(($editeurs ?? collect())->isNotEmpty())
                <p>✏️ Éditeurs: @foreach($editeurs as $ed) {{ $ed->name }}@if(!$loop->last), @endif @endforeach</p>
            @endif
            @if($evenement->categories->isNotEmpty())
                <p>🏷️ Catégories : @foreach($evenement->categories as $cat)<span class="categorie-tag">{{ $cat->libelle }}</span>@if(!$loop->last) @endif @endforeach</p>
            @endif
            <p>📝 Description: {{ $evenement->description }}</p>
            <p>👥 <a class="lien" href="{{ route('evenement.participants', $evenement->id) }}">{{ $evenement->inscriptions_count }} @if($evenement->max_participants != null)/ {{ $evenement->max_participants }}@endif participants</a></p>
        </div>
        <div class="event-actions">
            <a href="{{ route('accueil') }}"><button type="button" class="back-button">Retour</button></a>
            @auth
                @if($enregistre ?? false)
                    <form action="{{ route('desenregistrer', $evenement->id) }}" method="POST" style="display:inline">@csrf<button type="submit">Retirer des favoris</button></form>
                @else
                    <form action="{{ route('enregistrer', $evenement->id) }}" method="POST" style="display:inline">@csrf<button type="submit">Enregistrer (favoris)</button></form>
                @endif
                @if(!$inscrit)
                    @if($evenement->max_participants === null || $evenement->inscriptions_count < $evenement->max_participants)
                        <form action="{{ route('sInscrire', $evenement->id) }}" method="POST" style="display:inline">@csrf<button type="submit">Participer à l'événement</button></form>
                    @endif
                @else
                    <form action="{{ route('seDesinscrire', $evenement->id) }}" method="POST" style="display:inline">@csrf<button type="submit">Se désinscrire</button></form>
                @endif
                @if(($owned ?? false) || ($editeur ?? false))
                    <a href="{{ route('evenement.inviter', $evenement->id) }}"><button type="button">Inviter</button></a>
                    <a href="{{ route('evenement.modifier', $evenement->id) }}"><button type="button">Éditer</button></a>
                    <form action="{{ route('evenement.supprimer', $evenement->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Supprimer cet événement ?');">@csrf @method('DELETE')<button type="submit">Supprimer</button></form>
                    <a href="{{ route('evenement.participants', $evenement->id) }}"><button type="button">Participants</button></a>
                @endif
            @endauth
        </div>
    </div>
</div>
@endsection
