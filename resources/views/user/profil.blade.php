@extends('layouts.app')

@section('title', 'Profil — ' . $user->name)
@section('content')
<div id="profil">
    <div class="profile-details">
        <div class="profile-info">
            <h2>{{ $user->name }}</h2>
            <p>📧 Email: {{ $user->email }}</p>
            <p>📅 Membre depuis: {{ $user->created_at->format('d/m/Y') }}</p>
            @if(isset($nbSuivi))
                <p>👥 {{ $nbSuivi }} abonné(s)</p>
            @endif
        </div>
        <div class="profile-actions">
            @auth
                @if(auth()->id() !== $user->id)
                    @if($suivi ?? false)
                        <form action="{{ route('seDesabonner', $user->id) }}" method="POST" style="display:inline">@csrf<button type="submit">Ne plus suivre</button></form>
                    @elseif($demande ?? false)
                        <span>Demande en attente</span>
                    @else
                        <form action="{{ route('suivre', $user->id) }}" method="POST" style="display:inline">@csrf<button type="submit">Suivre</button></form>
                    @endif
                @else
                    @if($user->est_prive ?? false)
                        <form action="{{ route('passerPublic', $user->id) }}" method="POST" style="display:inline">@csrf<button type="submit">Passer en public</button></form>
                    @else
                        <form action="{{ route('passerPrive', $user->id) }}" method="POST" style="display:inline">@csrf<button type="submit">Passer en privé</button></form>
                    @endif
                    <a href="{{ route('demandes') }}"><button type="button">Demandes</button></a>
                @endif
            @endauth
            <a href="{{ url('/') }}"><button type="button" class="back-button">Retour</button></a>
        </div>
    </div>
    <div class="events-container">
        @if(($user->evenements ?? collect())->isEmpty())
            <p>{{ $user->name }} n'a pas encore créé d'événements.</p>
        @else
            @foreach($user->evenements ?? [] as $evenement)
            <a class="lien-discret" href="{{ route('evenement', $evenement->id) }}">
                <div class="event-card">
                    <h3>{{ $evenement->nom }}</h3>
                    <p>📍 {{ $evenement->ville ?? $evenement->lieu ?? '-' }}</p>
                    <p>📅 {{ is_object($evenement->date) ? $evenement->date->format('d/m/Y') : $evenement->date }}</p>
                    <p>🕒 {{ $evenement->heure }}</p>
                </div>
            </a>
            @endforeach
        @endif
    </div>
</div>
@endsection
