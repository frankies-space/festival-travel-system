<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Festivals') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($festivals->isEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-500">
                    {{ __('Er zijn nog geen festivals beschikbaar.') }}
                </div>
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($festivals as $festival)
                        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $festival->name }}</h3>
                                <p class="mt-1 text-sm text-gray-500">{{ $festival->location }}</p>
                                <p class="mt-3 text-sm text-gray-600">
                                    {{ $festival->start_date->format('d-m-Y') }} – {{ $festival->end_date->format('d-m-Y') }}
                                </p>
                                <div class="mt-4 flex items-center justify-between">
                                    <span class="text-sm {{ $festival->isFull() ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $festival->availableSpots() }} {{ __('plaatsen vrij') }}
                                    </span>
                                    <span class="text-sm font-medium text-gray-900">
                                        &euro; {{ number_format($festival->ticket_price, 2, ',', '.') }}
                                    </span>
                                </div>
                                <a href="{{ route('festivals.show', $festival) }}"
                                   class="mt-4 inline-block w-full text-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                                    {{ __('Bekijk details') }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
