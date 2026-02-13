<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $evenements = Evenement::query()
            ->whereDate('date', '>=', Carbon::today())
            ->orderBy('date')
            ->orderBy('heure')
            ->with('user')
            ->withCount('inscriptions')
            ->get();

        return view('welcome', ['evenements' => $evenements]);
    }
}
