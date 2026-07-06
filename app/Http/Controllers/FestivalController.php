<?php

namespace App\Http\Controllers;

use App\Models\Festival;
use Illuminate\View\View;

class FestivalController extends Controller
{
    public function index(): View
    {
        $festivals = Festival::query()
            ->orderBy('start_date')
            ->get();

        return view('festivals.index', compact('festivals'));
    }

    public function show(Festival $festival): View
    {
        return view('festivals.show', compact('festival'));
    }
}
