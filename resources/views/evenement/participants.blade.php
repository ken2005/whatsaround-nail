@extends('layouts.app')

@section('title', 'Participants — ' . $evenement->nom)

@section('content')
<div id="participants">
    <div class="participants-list">
        <h1>Participants — {{ $evenement->nom }}</h1>
        @if($participants->isEmpty())
            <p class="no-participants">Aucun participant.</p>
        @else
            @foreach($participants as $p)
                <div class="participant-card">
                    <div class="participant-info">
                        <div class="participant-details">
                            <h3>{{ $p->name }}</h3>
                            <p class="inscription-date">{{ $p->email }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
        <a href="{{ url("/evenement/{$evenement->id}") }}" class="return-button">Retour</a>
    </div>
</div>
@endsection
