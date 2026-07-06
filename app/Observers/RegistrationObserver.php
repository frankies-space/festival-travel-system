<?php

namespace App\Observers;

use App\Models\Registration;
use App\Services\BusPlanningService;

class RegistrationObserver
{
    public function __construct(private BusPlanningService $busPlanningService) {}

    public function created(Registration $registration): void
    {
        $registration->load('festival');

        $this->busPlanningService->handleNewRegistration($registration->festival);
    }
}
