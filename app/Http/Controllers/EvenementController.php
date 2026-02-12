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

    public function sInscrire(Request $request, int $id)
    {
        $evenement = Evenement::query()->findOrFail($id);
        if (! Auth::check()) {
            return redirect()->route('login');
        }
        $count = $evenement->inscriptions()->count();
        if ($evenement->max_participants !== null && $count >= $evenement->max_participants) {
            return back()->with('error', 'Plus de places disponibles.');
        }
        $evenement->inscriptions()->syncWithoutDetaching([Auth::id()]);
        return back()->with('message', 'Inscription enregistrée.');
    }

    public function seDesinscrire(Request $request, int $id)
    {
        $evenement = Evenement::query()->findOrFail($id);
        if (Auth::check()) {
            $evenement->inscriptions()->detach(Auth::id());
        }
        return back()->with('message', 'Désinscription enregistrée.');
    }
}
