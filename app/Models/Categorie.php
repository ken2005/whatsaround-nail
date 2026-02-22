<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Categorie extends Model
{
    protected $table = 'categorie';

    protected $fillable = ['libelle'];

    public function evenements(): BelongsToMany
    {
        return $this->belongsToMany(Evenement::class, 'etre', 'categorie_id', 'evenement_id');
    }
}
