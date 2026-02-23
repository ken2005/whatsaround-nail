<?php

namespace App\Http\Controllers;

use App\Core\Dictionnaires;
use App\Http\Requests\EvenementRequest;
use App\Mail\EvenementAnnule;
use App\Mail\EvenementModifie;
use App\Models\Categorie;
use App\Models\Diffusion;
use App\Models\Evenement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class EvenementController extends Controller
{
    public function creer()
    {
        if (! Auth::check()) {
            return redirect()->route('connexion');
        }
        $diffusions = Diffusion::orderBy('id')->get();
        $categories = Categorie::orderBy('libelle')->get();
        return view('evenement.creer', compact('diffusions', 'categories'));
    }

    public function doCreer(EvenementRequest $request)
    {
        if (! Auth::check()) {
            return redirect()->route('connexion');
        }
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['diffusion_id'] = (int) ($data['diffusion_id'] ?? 1);
        $data['annonciateur'] = $request->boolean('annonciateur');
        unset($data['image'], $data['categorie']);
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $dir = public_path('images/evenements');
            if (! is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $file = $request->file('image');
            $data['image'] = date('YmdHis') . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $file->move($dir, $data['image']);
        }
        $evenement = Evenement::query()->create($data);
        if ($request->filled('categorie')) {
            foreach ((array) $request->categorie as $categorieId) {
                DB::table('etre')->insert([
                    'evenement_id' => $evenement->id,
                    'categorie_id' => $categorieId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        return redirect()->route('accueil')->with('message', 'Événement créé.');
    }

    public function consulter(int $id)
    {
        Carbon::setLocale('fr');
        $evenement = Evenement::query()->with('user', 'diffusion', 'categories')->withCount('inscriptions')->findOrFail($id);
        $autorise = true;
        if (Auth::check()) {
            $diffusionId = (int) $evenement->diffusion_id;
            if ($diffusionId !== 1 && $evenement->user_id !== Auth::id()) {
                if ($diffusionId === 2) {
                    $autorise = DB::table('etre_invite')->where('evenement_id', $id)->where('user_id', Auth::id())->exists();
                } elseif ($diffusionId === 3) {
                    $autorise = DB::table('suivre')->where('followed_id', $evenement->user_id)->where('follower_id', Auth::id())->where('validee', 1)->exists();
                }
            }
        } else {
            if ((int) $evenement->diffusion_id !== 1) {
                $autorise = false;
            }
        }
        if (! $autorise) {
            abort(403);
        }
        $owned = Auth::check() && $evenement->user_id === Auth::id();
        $passed = $evenement->date < now();
        $enregistre = Auth::check() && DB::table('enregistrer')->where('evenement_id', $id)->where('user_id', Auth::id())->exists();
        $inscrit = Auth::check() && $evenement->inscriptions()->where('user_id', Auth::id())->exists();
        $dateFormatted = Carbon::parse($evenement->date)->isoFormat('D MMMM YYYY');
        $editeurs = collect();
        if (Auth::check()) {
            $editeurs = DB::table('editer')
                ->join('users', 'editer.user_id', '=', 'users.id')
                ->where('editer.evenement_id', $id)
                ->select('users.name', 'users.id')
                ->get();
        }
        $editeur = $editeurs->contains('id', Auth::id());
        return view('evenement.consulter', [
            'evenement' => $evenement,
            'owned' => $owned,
            'passed' => $passed,
            'enregistre' => $enregistre,
            'inscrit' => $inscrit,
            'dateFormatted' => $dateFormatted,
            'editeurs' => $editeurs,
            'editeur' => $editeur,
        ]);
    }

    public function sInscrire(Request $request, int $id)
    {
        $evenement = Evenement::query()->findOrFail($id);
        if (! Auth::check()) {
            return redirect()->route('connexion');
        }
        $diffusionId = (int) $evenement->diffusion_id;
        if ($diffusionId === 2) {
            if (! DB::table('etre_invite')->where('evenement_id', $id)->where('user_id', Auth::id())->exists()) {
                return back()->with('error', 'Vous n\'êtes pas invité à cet événement.');
            }
        } elseif ($diffusionId === 3) {
            if (! DB::table('suivre')->where('followed_id', $evenement->user_id)->where('follower_id', Auth::id())->where('validee', 1)->exists()) {
                return back()->with('error', 'Vous ne suivez pas l\'organisateur.');
            }
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
        $evenement = Evenement::query()->with('categories')->findOrFail($id);
        if ($evenement->user_id !== Auth::id() && ! DB::table('editer')->where('evenement_id', $id)->where('user_id', Auth::id())->exists()) {
            abort(403);
        }
        $diffusions = Diffusion::orderBy('id')->get();
        $categories = Categorie::orderBy('libelle')->get();
        return view('evenement.modifier', compact('evenement', 'diffusions', 'categories'));
    }

    public function doModifier(EvenementRequest $request, int $id)
    {
        $evenement = Evenement::query()->findOrFail($id);
        if ($evenement->user_id !== Auth::id() && ! DB::table('editer')->where('evenement_id', $id)->where('user_id', Auth::id())->exists()) {
            abort(403);
        }
        $data = $request->validated();
        $data['annonciateur'] = $request->boolean('annonciateur');
        unset($data['image']);
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $dir = public_path('images/evenements');
            if (! is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            if ($evenement->image) {
                $old = $dir . '/' . $evenement->image;
                if (is_file($old)) {
                    @unlink($old);
                }
            }
            $file = $request->file('image');
            $data['image'] = date('YmdHis') . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $file->move($dir, $data['image']);
        }
        $evenement->update($data);
        DB::table('etre')->where('evenement_id', $id)->delete();
        if ($request->filled('categorie')) {
            foreach ((array) $request->categorie as $categorieId) {
                DB::table('etre')->insert([
                    'evenement_id' => $id,
                    'categorie_id' => $categorieId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        $evenement->refresh();
        foreach ($evenement->inscriptions as $user) {
            if ($user->email) {
                Mail::to($user->email)->send(new EvenementModifie($evenement));
            }
        }
        return redirect()->route('evenement', $id)->with('message', 'Événement modifié.');
    }

    public function supprimer(int $id)
    {
        $evenement = Evenement::query()->with('inscriptions')->findOrFail($id);
        if ($evenement->user_id !== Auth::id() && ! DB::table('editer')->where('evenement_id', $id)->where('user_id', Auth::id())->exists()) {
            abort(403);
        }
        $nomEvenement = $evenement->nom;
        $dateHeure = $evenement->date->format('d/m/Y') . ' à ' . $evenement->heure;
        foreach ($evenement->inscriptions as $user) {
            if ($user->email) {
                Mail::to($user->email)->send(new EvenementAnnule($nomEvenement, $dateHeure));
            }
        }
        DB::table('enregistrer')->where('evenement_id', $id)->delete();
        $evenement->delete();
        return redirect()->route('accueil')->with('message', 'Événement supprimé.');
    }

    public function participants(int $id)
    {
        $evenement = Evenement::query()->with('inscriptions')->findOrFail($id);
        if ($evenement->user_id !== Auth::id() && ! DB::table('editer')->where('evenement_id', $id)->where('user_id', Auth::id())->exists()) {
            abort(403);
        }
        return view('evenement.participants', ['evenement' => $evenement, 'participants' => $evenement->inscriptions]);
    }

    public function inscriptions()
    {
        if (! Auth::check()) {
            return redirect()->route('connexion');
        }
        Carbon::setLocale('fr');
        $evenements = Auth::user()->inscriptions()->whereDate('date', '>=', now())->orderBy('date')->orderBy('heure')->get();
        foreach ($evenements as $e) {
            $e->date = Carbon::parse($e->date)->isoFormat('D MMMM YYYY');
        }
        return view('evenement.inscriptions', compact('evenements'));
    }

    public function crees()
    {
        if (! Auth::check()) {
            return redirect()->route('connexion');
        }
        Carbon::setLocale('fr');
        $evenements = Auth::user()->evenements()->orderBy('date')->orderBy('heure')->get();
        $edites = DB::table('editer')
            ->join('evenements', 'editer.evenement_id', '=', 'evenements.id')
            ->where('editer.user_id', Auth::id())
            ->select('evenements.*')
            ->get();
        $all = $evenements->merge($edites)->sortBy('date')->values();
        foreach ($all as $e) {
            $e->date = Carbon::parse($e->date)->isoFormat('D MMMM YYYY');
        }
        return view('evenement.crees', ['evenements' => $all]);
    }

    public function enregistrer(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->route('connexion');
        }
        $evenement = Evenement::query()->findOrFail($id);
        if (DB::table('enregistrer')->where('user_id', Auth::id())->where('evenement_id', $id)->exists()) {
            return back()->with('message', 'Déjà enregistré.');
        }
        DB::table('enregistrer')->insert([
            'user_id' => Auth::id(),
            'evenement_id' => $id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return back()->with('message', 'Événement enregistré.');
    }

    public function desenregistrer(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->route('connexion');
        }
        DB::table('enregistrer')->where('user_id', Auth::id())->where('evenement_id', $id)->delete();
        return back()->with('message', 'Événement retiré des favoris.');
    }

    public function enregistres(): \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->route('connexion');
        }
        Carbon::setLocale('fr');
        $evenements = DB::table('evenements')
            ->join('enregistrer', 'evenements.id', '=', 'enregistrer.evenement_id')
            ->where('enregistrer.user_id', Auth::id())
            ->select('evenements.*')
            ->orderBy('evenements.date')
            ->get();
        foreach ($evenements as $e) {
            $e->date = Carbon::parse($e->date)->isoFormat('D MMMM YYYY');
        }
        return view('evenement.enregistres', ['evenements' => $evenements]);
    }

    public function inviter(int $id): \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
    {
        $evenement = Evenement::query()->findOrFail($id);
        if ($evenement->user_id !== Auth::id()) {
            abort(403);
        }
        $users = DB::table('users')
            ->join('suivre', 'users.id', '=', 'suivre.followed_id')
            ->where('suivre.follower_id', Auth::id())
            ->where('suivre.validee', 1)
            ->whereNotIn('users.id', function ($q) use ($id) {
                $q->select('user_id')->from('etre_invite')->where('evenement_id', $id);
            })
            ->select('users.*')
            ->get();
        return view('evenement.inviter', ['evenement' => $evenement, 'users' => $users]);
    }

    public function doInviter(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $evenement = Evenement::query()->findOrFail($id);
        if ($evenement->user_id !== Auth::id()) {
            abort(403);
        }
        $request->validate([
            'users' => 'required|array',
            'users.*' => 'exists:users,id',
        ]);
        foreach ($request->users as $userId) {
            if (! DB::table('etre_invite')->where('evenement_id', $id)->where('user_id', $userId)->exists()) {
                DB::table('etre_invite')->insert([
                    'user_id' => $userId,
                    'evenement_id' => $id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        return redirect()->route('evenement', $id)->with('message', 'Invitations envoyées.');
    }

    public function rechercher(Request $request): \Illuminate\Contracts\View\View
    {
        Carbon::setLocale('fr');
        $search = $request->input('search', '');
        $searchTerms = $search ? array_filter(explode(' ', $search)) : [];
        $evenements = collect();
        $users = collect();

        if (count($searchTerms) > 0) {
            $moisFr = Dictionnaires::getMoisFrancais();
            $joursFr = Dictionnaires::getJoursFrancais();
            $query = Evenement::query()
                ->leftJoin('etre', 'evenements.id', '=', 'etre.evenement_id')
                ->leftJoin('categorie', 'etre.categorie_id', '=', 'categorie.id')
                ->where(function ($q) use ($searchTerms) {
                    $q->where('evenements.diffusion_id', 1);
                    if (Auth::check()) {
                        $q->orWhere(function ($q2) {
                            $q2->where('evenements.diffusion_id', 2)
                                ->whereExists(fn ($sub) => $sub->select(DB::raw(1))->from('etre_invite')
                                    ->whereColumn('etre_invite.evenement_id', 'evenements.id')
                                    ->where('etre_invite.user_id', Auth::id()));
                        })
                            ->orWhere(function ($q2) {
                                $q2->where('evenements.diffusion_id', 3)
                                    ->whereExists(fn ($sub) => $sub->select(DB::raw(1))->from('suivre')
                                        ->whereColumn('suivre.followed_id', 'evenements.user_id')
                                        ->where('suivre.follower_id', Auth::id())
                                        ->where('suivre.validee', 1));
                            });
                    }
                });

            foreach ($searchTerms as $term) {
                $termLike = '%' . $term . '%';
                $moisEn = null;
                foreach ($moisFr as $m) {
                    if (stripos($m, $term) !== false) {
                        $moisEn = Dictionnaires::getMois($m);
                        break;
                    }
                }
                $jourEn = null;
                foreach ($joursFr as $j) {
                    if (stripos($j, $term) !== false) {
                        $jourEn = Dictionnaires::getJours($j);
                        break;
                    }
                }
                $query->where(function ($q) use ($termLike, $term, $moisEn, $jourEn) {
                    $q->where('evenements.nom', 'like', $termLike)
                        ->orWhere('evenements.description', 'like', $termLike)
                        ->orWhere('evenements.lieu', 'like', $termLike)
                        ->orWhere('evenements.ville', 'like', $termLike)
                        ->orWhere('evenements.code_postal', 'like', $termLike)
                        ->orWhere('evenements.allee', 'like', $termLike)
                        ->orWhere('categorie.libelle', 'like', $termLike);
                    if ($moisEn) {
                        $monthNum = Carbon::parse($moisEn . ' 1')->month;
                        $q->orWhereMonth('evenements.date', $monthNum);
                    }
                    if ($jourEn) {
                        $dayOfWeek = Carbon::parse($jourEn)->dayOfWeek;
                        $driver = DB::connection()->getDriverName();
                        if ($driver === 'sqlite') {
                            $q->orWhereRaw('strftime(\'%w\', evenements.date) = ?', [(string) $dayOfWeek]);
                        } else {
                            $q->orWhereRaw('DAYOFWEEK(evenements.date) = ?', [$dayOfWeek + 1]);
                        }
                    }
                });
            }

            $evenements = $query->select('evenements.*')->orderBy('evenements.date')->get()->unique('id')->values();
            $evenements->load('categories');
            foreach ($evenements as $e) {
                $e->date_formatted = Carbon::parse($e->date)->locale('fr')->isoFormat('D MMMM YYYY');
            }

            $users = User::query()->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $t) {
                    $q->where('name', 'like', '%' . $t . '%');
                }
            })->get();
        }

        return view('evenement.recherche', ['evenements' => $evenements, 'users' => $users, 'search' => $search]);
    }
}
