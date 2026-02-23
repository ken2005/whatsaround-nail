@extends('layouts.app')

@section('title', 'Créer un événement')

@section('content')
<div id="creer-evenement">
    <div class="form-container">
        <h2 class="event-title">Créer un événement</h2>
        @if ($errors->any())
            <ul class="alert-error">@foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
        @endif
        <form method="POST" action="{{ route('doCreer') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="nom">Nom <span class="required">*</span></label>
                <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required>
                @error('nom')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="description">Description <span class="required">*</span></label>
                <textarea id="description" name="description" required>{{ old('description') }}</textarea>
                @error('description')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="date">Date <span class="required">*</span></label>
                <input type="date" id="date" name="date" value="{{ old('date') }}" required>
                @error('date')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="heure">Heure <span class="required">*</span></label>
                <input type="time" id="heure" name="heure" value="{{ old('heure') }}" required>
                @error('heure')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="lieu">Lieu ou adresse courte <span class="required">*</span></label>
                <input type="text" id="lieu" name="lieu" value="{{ old('lieu') }}" placeholder="ex. Salle des fêtes" required>
                @error('lieu')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <fieldset class="form-group">
                <legend>Adresse détaillée (optionnel)</legend>
                <div class="form-group"><label for="num_rue">N° et rue</label><input type="text" id="num_rue" name="num_rue" value="{{ old('num_rue') }}" placeholder="ex. 12 rue de la Paix"></div>
                <div class="form-group"><label for="allee">Complément (allée, bât.)</label><input type="text" id="allee" name="allee" value="{{ old('allee') }}"></div>
                <div class="form-group"><label for="ville">Ville</label><input type="text" id="ville" name="ville" value="{{ old('ville') }}"></div>
                <div class="form-group"><label for="code_postal">Code postal</label><input type="text" id="code_postal" name="code_postal" value="{{ old('code_postal') }}" maxlength="10"></div>
                <div class="form-group"><label for="pays">Pays</label><input type="text" id="pays" name="pays" value="{{ old('pays', 'France') }}"></div>
            </fieldset>
            <div class="form-group">
                <label for="diffusion_id">Visibilité <span class="required">*</span></label>
                <select id="diffusion_id" name="diffusion_id" required>
                    @foreach($diffusions as $d)
                        <option value="{{ $d->id }}" {{ old('diffusion_id', 1) == $d->id ? 'selected' : '' }}>{{ $d->libelle }}</option>
                    @endforeach
                </select>
                <small>Public = visible par tous ; Invités = uniquement les personnes invitées ; Abonnés = vos abonnés.</small>
                @error('diffusion_id')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Catégories (optionnel)</label>
                <div class="categories-container">
                    <div class="categories-wrapper">
                        <div class="search-container">
                            <input type="text" id="searchCategories" class="form-control" placeholder="Rechercher des catégories...">
                        </div>
                        <div class="checkbox-list">
                            @foreach($categories as $cat)
                            <div class="checkbox-item">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="categorie[]" value="{{ $cat->id }}" {{ in_array($cat->id, old('categorie', [])) ? 'checked' : '' }}>
                                    <span class="checkbox-text">{{ $cat->libelle }}</span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @error('categorie')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="image">Image de l'événement (optionnel, 2 Mo max)</label>
                <div class="custom-file-input">
                    <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" onchange="previewImage(this)">
                    <span class="file-label">Choisir une image</span>
                </div>
                <div class="image-preview" id="imagePreview">Aperçu de l'image</div>
                @error('image')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="annonciateur-label">
                    <input type="checkbox" id="annonciateur" name="annonciateur" value="1" {{ old('annonciateur') ? 'checked' : '' }}>
                    <span class="checkbox-text">Je ne suis qu'annonciateur</span>
                </label>
                @error('annonciateur')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="max_participants">Places max (optionnel)</label>
                <input type="number" id="max_participants" name="max_participants" value="{{ old('max_participants') }}" min="1" placeholder="Illimité si vide">
                @error('max_participants')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <div class="button-group">
                <button type="submit">Créer l'événement</button>
                <a href="{{ route('accueil') }}"><button type="button" class="back-button">Retour</button></a>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
(function() {
  var searchInput = document.getElementById('searchCategories');
  if (searchInput) {
    searchInput.addEventListener('keyup', function() {
      var searchText = this.value.toLowerCase();
      document.querySelectorAll('#creer-evenement .checkbox-item').forEach(function(item) {
        var label = item.querySelector('.checkbox-text');
        var text = label ? label.textContent.toLowerCase() : '';
        item.style.display = text.indexOf(searchText) !== -1 ? '' : 'none';
      });
    });
  }
  function previewImage(input) {
    var preview = document.getElementById('imagePreview');
    var fileLabel = input.parentElement.querySelector('.file-label');
    if (!preview || !fileLabel) return;
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        preview.style.backgroundImage = "url('" + e.target.result + "')";
        preview.textContent = '';
        fileLabel.textContent = input.files[0].name;
      };
      reader.readAsDataURL(input.files[0]);
    } else {
      preview.style.backgroundImage = 'none';
      preview.textContent = "Aperçu de l'image";
      fileLabel.textContent = 'Choisir une image';
    }
  }
  window.previewImage = previewImage;
})();
</script>
@endpush
@endsection
