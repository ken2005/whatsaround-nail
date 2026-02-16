<?php

namespace App\Http\Controllers;

use App\Models\User;

class UtilisateurController extends Controller
{
    public function profil(int $id)
    {
        $user = User::query()->with('evenements')->findOrFail($id);
        return view('user.profil', compact('user'));
    }
}
