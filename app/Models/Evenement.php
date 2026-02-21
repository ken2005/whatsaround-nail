<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Evenement extends Model
{
    use HasFactory;

    protected $table = 'evenements';

    protected $fillable = [
        'nom',
        'description',
        'date',
        'heure',
        'lieu',
        'num_rue',
        'allee',
        'ville',
        'code_postal',
        'pays',
        'image',
        'user_id',
        'diffusion_id',
        'annonciateur',
        'max_participants',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'max_participants' => 'integer',
            'annonciateur' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function diffusion(): BelongsTo
    {
        return $this->belongsTo(Diffusion::class);
    }

    public function inscriptions(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 's_inscrire')
            ->withTimestamps();
    }

    public function enregistrements(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'enregistrer', 'evenement_id', 'user_id')
            ->withTimestamps();
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Categorie::class, 'etre', 'evenement_id', 'categorie_id');
    }
}
