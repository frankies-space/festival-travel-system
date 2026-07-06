<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Festivalbeheer') }}</h2>
            <a href="{{ route('planner.festivals.create') }}"
               class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                {{ __('Nieuw festival') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status') === 'festival-created')
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
                    {{ __('Festival aangemaakt.') }}
                </div>
            @endif
            @if (session('status') === 'festival-updated')
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
                    {{ __('Festival bijgewerkt.') }}
                </div>
            @endif
            @if (session('status') === 'festival-deleted')
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
                    {{ __('Festival verwijderd.') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Festival') }}</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Datum') }}</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Aanmeldingen') }}</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">{{ __('Busreizen') }}</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-500">{{ __('Acties') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($festivals as $festival)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900">{{ $festival->name }}</div>
                                    <div class="text-gray-500">{{ $festival->location }}</div>
                                </td>
                                <td class="px-4 py-3">{{ $festival->start_date->format('d-m-Y') }}</td>
                                <td class="px-4 py-3">{{ $festival->registrations_count }}</td>
                                <td class="px-4 py-3">{{ $festival->bus_trips_count }}</td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <a href="{{ route('planner.festivals.show', $festival) }}" class="text-indigo-600 hover:text-indigo-800">Bekijk</a>
                                    <a href="{{ route('planner.festivals.edit', $festival) }}" class="text-indigo-600 hover:text-indigo-800">Bewerk</a>
                                    <form method="POST" action="{{ route('planner.festivals.destroy', $festival) }}" class="inline"
                                          onsubmit="return confirm('Weet je zeker dat je dit festival wilt verwijderen?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">Verwijder</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">{{ __('Geen festivals gevonden.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
