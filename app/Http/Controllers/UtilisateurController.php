<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UtilisateurController extends Controller
{
    public function profil(int $id)
    {
        Carbon::setLocale('fr');
        $user = User::query()->find($id);
        if (! $user) {
            abort(404);
        }
        if (! Auth::check()) {
            return redirect()->route('connexion');
        }
        $suivi = DB::table('suivre')->where('follower_id', Auth::id())->where('followed_id', $id)->where('validee', 1)->exists();
        $demande = DB::table('suivre')->where('follower_id', Auth::id())->where('followed_id', $id)->where('validee', 0)->exists();
        $evenements = DB::table('evenements')
            ->leftJoin('suivre', 'evenements.user_id', '=', 'suivre.followed_id')
            ->leftJoin('etre_invite', 'evenements.id', '=', 'etre_invite.evenement_id')
            ->where('evenements.user_id', $id)
            ->where(function ($query) {
                $query->where('evenements.diffusion_id', 1)
                    ->orWhere('evenements.user_id', Auth::id())
                    ->orWhere(function ($q) {
                        $q->where('suivre.follower_id', Auth::id())
                            ->where('suivre.validee', 1)
                            ->where('evenements.diffusion_id', 3);
                    })
                    ->orWhere(function ($q) {
                        $q->where('etre_invite.user_id', Auth::id())
                            ->where('evenements.diffusion_id', 2);
                    });
            })
            ->select('evenements.*')
            ->distinct()
            ->orderBy('evenements.date')
            ->get();
        foreach ($evenements as $e) {
            $e->date = Carbon::parse($e->date)->isoFormat('D MMMM YYYY');
        }
        $user->evenements = $evenements;
        $nbSuivi = DB::table('suivre')->where('followed_id', $id)->where('validee', 1)->count();
        return view('user.profil', ['user' => $user, 'suivi' => $suivi, 'nbSuivi' => $nbSuivi, 'demande' => $demande]);
    }

    public function suivre(Request $request, int $id)
    {
        if (! Auth::check()) {
            return redirect()->route('connexion');
        }
        if (Auth::id() === $id) {
            return back();
        }
        $exists = DB::table('suivre')->where('follower_id', Auth::id())->where('followed_id', $id)->exists();
        if ($exists) {
            return back();
        }
        $validee = User::find($id)?->est_prive ? 0 : 1;
        DB::table('suivre')->insert([
            'follower_id' => Auth::id(),
            'followed_id' => $id,
            'validee' => $validee,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return back();
    }

    public function seDesabonner(Request $request, int $id)
    {
        if (! Auth::check()) {
            return redirect()->route('connexion');
        }
        DB::table('suivre')
            ->where('follower_id', Auth::id())
            ->where('followed_id', $id)
            ->delete();
        return back();
    }

    public function abonnements()
    {
        if (! Auth::check()) {
            return redirect()->route('connexion');
        }
        $following = DB::table('users')
            ->join('suivre', 'users.id', '=', 'suivre.followed_id')
            ->where('suivre.follower_id', Auth::id())
            ->where('suivre.validee', 1)
            ->select('users.name', 'users.id', 'users.image')
            ->get();
        return view('user.abonnements', ['following' => $following]);
    }

    public function demandes()
    {
        if (! Auth::check()) {
            return redirect()->route('connexion');
        }
        if (! Auth::user()->est_prive) {
            return redirect()->route('abonnements');
        }
        $demandes = DB::table('suivre')
            ->join('users', 'suivre.follower_id', '=', 'users.id')
            ->where('suivre.followed_id', Auth::id())
            ->where('suivre.validee', 0)
            ->select('users.id', 'users.name', 'users.image')
            ->get()
            ->map(fn ($d) => (object) [
                'id' => $d->id,
                'follower' => (object) ['name' => $d->name, 'image' => $d->image ?? 'profile.png'],
            ]);
        return view('user.demandes', ['demandes' => $demandes]);
    }

    public function accepterDemande(Request $request, int $id)
    {
        if (! Auth::check()) {
            return redirect()->route('connexion');
        }
        DB::table('suivre')
            ->where('follower_id', $id)
            ->where('followed_id', Auth::id())
            ->update(['validee' => 1, 'updated_at' => now()]);
        return back();
    }

    public function refuserDemande(Request $request, int $id)
    {
        if (! Auth::check()) {
            return redirect()->route('connexion');
        }
        DB::table('suivre')
            ->where('follower_id', $id)
            ->where('followed_id', Auth::id())
            ->delete();
        return back();
    }

    public function passerPrive(Request $request, int $id)
    {
        if (! Auth::check() || Auth::id() !== $id) {
            return redirect()->route('connexion');
        }
        DB::table('users')->where('id', Auth::id())->update(['est_prive' => 1, 'updated_at' => now()]);
        return back();
    }

    public function passerPublic(Request $request, int $id)
    {
        if (! Auth::check() || Auth::id() !== $id) {
            return redirect()->route('connexion');
        }
        DB::table('users')->where('id', Auth::id())->update(['est_prive' => 0, 'updated_at' => now()]);
        return back();
    }
}
