<?php

namespace App\Http\Controllers;

use App\Http\Requests\RedeemDiscountRequest;
use App\Services\PointsService;
use Illuminate\Http\RedirectResponse;
use InvalidArgumentException;

class PointsController extends Controller
{
    public function redeemDiscount(RedeemDiscountRequest $request, PointsService $pointsService): RedirectResponse
    {
        $user = $request->user();

        try {
            $discount = $pointsService->redeemForDiscount($user, (int) $request->validated('points'));
        } catch (InvalidArgumentException $e) {
            return redirect()->route('profile.edit')->withErrors(['points' => $e->getMessage()]);
        }

        return redirect()->route('profile.edit')
            ->with('status', 'discount-redeemed')
            ->with('discount_amount', $discount);
    }

    public function redeemVip(PointsService $pointsService): RedirectResponse
    {
        $user = auth()->user();

        try {
            $pointsService->redeemForVip($user);
        } catch (InvalidArgumentException $e) {
            return redirect()->route('profile.edit')->withErrors(['vip' => $e->getMessage()]);
        }

        return redirect()->route('profile.edit')->with('status', 'vip-redeemed');
    }
}
