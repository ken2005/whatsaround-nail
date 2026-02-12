<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Http\Requests\EvenementRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvenementController extends Controller
{
    public function creer()
    {
        return view('evenement.creer');
    }

    public function doCreer(EvenementRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        Evenement::query()->create($data);
        return redirect('/')->with('message', 'Événement créé.');
    }

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

    public function modifier(int $id)
    {
        $evenement = Evenement::query()->findOrFail($id);
        if ($evenement->user_id !== Auth::id()) { abort(403); }
        return view('evenement.modifier', compact('evenement'));
    }

    public function doModifier(EvenementRequest $request, int $id)
    {
        $evenement = Evenement::query()->findOrFail($id);
        if ($evenement->user_id !== Auth::id()) { abort(403); }
        $evenement->update($request->validated());
        return redirect()->to("/evenement/{$id}")->with('message', 'Événement modifié.');
    }

    public function supprimer(int $id)
    {
        $evenement = Evenement::query()->findOrFail($id);
        if ($evenement->user_id !== Auth::id()) { abort(403); }
        $evenement->delete();
        return redirect('/')->with('message', 'Événement supprimé.');
    }

    public function participants(int $id)
    {
        $evenement = Evenement::query()->with('inscriptions')->findOrFail($id);
        if ($evenement->user_id !== Auth::id()) { abort(403); }
        return view('evenement.participants', ['evenement' => $evenement, 'participants' => $evenement->inscriptions]);
    }

    public function inscriptions()
    {
        $evenements = Auth::user()->inscriptions()->whereDate('date', '>=', now())->orderBy('date')->orderBy('heure')->get();
        return view('evenement.inscriptions', compact('evenements'));
    }

    public function crees()
    {
        $evenements = Auth::user()->evenements()->orderBy('date')->orderBy('heure')->get();
        return view('evenement.crees', compact('evenements'));
    }
}
