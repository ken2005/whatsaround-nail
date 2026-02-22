<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Événement modifié</title>
</head>
<body>
    <h1>Événement modifié</h1>
    <p>L'événement <strong>{{ $evenement->nom }}</strong> auquel vous êtes inscrit a été modifié.</p>
    <p>Date : {{ $evenement->date?->format('d/m/Y') }} à {{ $evenement->heure }}</p>
    <p>Lieu : {{ $evenement->lieu ?? ($evenement->ville ? $evenement->num_rue . ' ' . $evenement->allee . ', ' . $evenement->ville : '-') }}</p>
    <p><a href="{{ url('/evenement/'.$evenement->id) }}">Voir l'événement</a></p>
</body>
</html>
