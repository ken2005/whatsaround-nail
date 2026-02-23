<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $timezone = config('app.timezone', 'UTC');
        $today = Carbon::today($timezone)->format('Y-m-d');

        $query = Evenement::query()
            ->where('date', '>=', $today)
            ->orderBy('date')
            ->orderBy('heure')
            ->with('user', 'categories')
            ->withCount('inscriptions');

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(function ($q) use ($term) {
                $q->where('nom', 'like', "%{$term}%")
                    ->orWhere('lieu', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
            });
        }

        $evenements = $query->get();

        return view('welcome', ['evenements' => $evenements]);
    }
}
