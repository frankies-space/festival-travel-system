<?php

namespace App\Http\Controllers\Planner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Planner\StoreFestivalRequest;
use App\Http\Requests\Planner\UpdateFestivalRequest;
use App\Models\Festival;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FestivalController extends Controller
{
    public function index(): View
    {
        $festivals = Festival::query()
            ->withCount(['registrations', 'bookings', 'busTrips'])
            ->orderBy('start_date')
            ->get();

        return view('planner.festivals.index', compact('festivals'));
    }

    public function create(): View
    {
        return view('planner.festivals.create');
    }

    public function store(StoreFestivalRequest $request): RedirectResponse
    {
        Festival::create($request->validated());

        return redirect()
            ->route('planner.festivals.index')
            ->with('status', 'festival-created');
    }

    public function show(Festival $festival): View
    {
        $festival->load('busTrips');
        $festival->loadCount(['registrations', 'bookings', 'busTrips']);

        return view('planner.festivals.show', compact('festival'));
    }

    public function edit(Festival $festival): View
    {
        return view('planner.festivals.edit', compact('festival'));
    }

    public function update(UpdateFestivalRequest $request, Festival $festival): RedirectResponse
    {
        $festival->update($request->validated());

        return redirect()
            ->route('planner.festivals.index')
            ->with('status', 'festival-updated');
    }

    public function destroy(Festival $festival): RedirectResponse
    {
        $festival->delete();

        return redirect()
            ->route('planner.festivals.index')
            ->with('status', 'festival-deleted');
    }
}
