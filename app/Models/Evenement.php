<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Evenement extends Model
{
    protected $table = 'evenements';

    protected $fillable = [
        'nom',
        'description',
        'date',
        'heure',
        'lieu',
        'user_id',
        'max_participants',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'max_participants' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function inscriptions(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 's_inscrire')
            ->withTimestamps();
    }
}
