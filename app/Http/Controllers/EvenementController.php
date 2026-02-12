<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvenementController extends Controller
{
    public function consulter(int $id)
    {
        $evenement = Evenement::query()->with('user')->withCount('inscriptions')->findOrFail($id);
        $inscrit = Auth::check() && $evenement->inscriptions()->where('user_id', Auth::id())->exists();
        return view('evenement.consulter', compact('evenement', 'inscrit'));
    }
}
