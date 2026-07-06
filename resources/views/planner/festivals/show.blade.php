<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $festival->name }}</h2>
            <a href="{{ route('planner.festivals.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">&larr; Terug</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <dl class="grid gap-4 sm:grid-cols-2 text-sm">
                    <div>
                        <dt class="font-medium text-gray-500">Locatie</dt>
                        <dd class="mt-1">{{ $festival->location }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Datum</dt>
                        <dd class="mt-1">{{ $festival->start_date->format('d-m-Y') }} – {{ $festival->end_date->format('d-m-Y') }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Capaciteit</dt>
                        <dd class="mt-1">{{ $festival->max_capacity }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Ticketprijs</dt>
                        <dd class="mt-1">&euro; {{ number_format($festival->ticket_price, 2, ',', '.') }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Aanmeldingen</dt>
                        <dd class="mt-1">{{ $festival->registrations_count }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Busreizen</dt>
                        <dd class="mt-1">{{ $festival->bus_trips_count }}</dd>
                    </div>
                </dl>
                @if ($festival->description)
                    <p class="mt-4 text-sm text-gray-700">{{ $festival->description }}</p>
                @endif
            </div>

            @if ($festival->busTrips->isNotEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-medium text-gray-900 mb-4">Geplande busreizen</h3>
                    <ul class="space-y-2 text-sm">
                        @foreach ($festival->busTrips as $busTrip)
                            <li class="flex justify-between border-b border-gray-100 pb-2">
                                <span>{{ $busTrip->departure_location }} – {{ $busTrip->departure_date->format('d-m-Y') }}</span>
                                <span class="text-gray-500">{{ $busTrip->bookings()->count() }} / {{ $busTrip->max_passengers }} passagiers</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
