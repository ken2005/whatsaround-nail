<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Diffusion extends Model
{
    protected $table = 'diffusion';

    protected $fillable = ['libelle'];

    public function evenements(): HasMany
    {
        return $this->hasMany(Evenement::class, 'diffusion_id');
    }
}
