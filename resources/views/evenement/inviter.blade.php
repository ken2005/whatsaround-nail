@extends('layouts.app')

@section('title', 'Inviter - ' . $evenement->nom)
@section('content')
<div id="inviter-utilisateurs">
    <div class="form-container">
        <h2 class="page-title">Inviter des utilisateurs</h2>
        <form action="{{ route('evenement.doInviter', $evenement->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="utilisateurs">Utilisateurs (personnes que vous suivez)</label>
                <div class="select-container">
                    <div class="select-box">
                        <div class="search-box">
                            <input type="text" id="searchInput" class="form-control" placeholder="Rechercher des utilisateurs...">
                        </div>
                        <div class="checkbox-list">
                            @if ($users->isEmpty())
                                <p class="no-users">Aucun utilisateur à inviter (suivez des comptes pour les inviter).</p>
                            @endif
                            @foreach ($users as $user)
                            <div class="checkbox-item">
                                <label>
                                    <input type="checkbox" name="users[]" value="{{ $user->id }}" {{ is_array(old('users')) && in_array($user->id, old('users')) ? 'checked' : '' }}>
                                    <span class="user-name">{{ $user->name }}</span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @error('users')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="button-group">
                <button type="submit">Inviter</button>
                <a href="{{ route('evenement', $evenement->id) }}"><button type="button" class="back-button">Retour</button></a>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
(function() {
  var searchInput = document.getElementById('searchInput');
  if (searchInput) {
    searchInput.addEventListener('keyup', function() {
      var searchText = this.value.toLowerCase();
      document.querySelectorAll('.checkbox-item').forEach(function(item) {
        var label = item.querySelector('label');
        var text = label ? label.textContent.toLowerCase() : '';
        item.style.display = text.indexOf(searchText) !== -1 ? '' : 'none';
      });
    });
  }
})();
</script>
@endpush
@endsection
