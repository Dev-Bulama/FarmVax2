<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\VaccinationHistory;
use App\Models\Livestock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VaccinationController extends Controller
{
    public function index(Request $request)
    {
        $query = VaccinationHistory::whereHas('livestock', function($q) {
            $q->where('user_id', Auth::id());
        })->with('livestock')->orderBy('vaccination_date', 'desc');

        if ($request->filled('search')) {
            $query->where('vaccine_name', 'like', '%' . $request->search . '%');
        }

        $vaccinations = $query->paginate(15);
        
        // Get livestock for the user
        $livestock = Livestock::where('user_id', Auth::id())->get();

        return view('individual.vaccinations.index', compact('vaccinations', 'livestock'));
    }
}