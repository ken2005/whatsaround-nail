@extends('layouts.app')

@section('title', 'Comptes suivis')
@section('content')
<h1 class="mb-4 text-center">Comptes suivis</h1>
@if ($following->isEmpty())
    <p class="text-center no-following mb-4">Vous ne suivez aucun compte pour le moment.</p>
@else
<div class="profiles-container">
    @foreach ($following as $user)
    <a href="{{ route('profil', $user->id) }}" class="lien-discret">
        <div class="profile-card">
            <img src="{{ asset('images/profils/' . ($user->image ?? 'profile.png')) }}" alt="Avatar" width="150" height="150">
            <h3>{{ $user->name }}</h3>
            <form action="{{ route('seDesabonner', $user->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-danger">Ne plus suivre</button>
            </form>
        </div>
    </a>
    @endforeach
</div>
@endif
@endsection
