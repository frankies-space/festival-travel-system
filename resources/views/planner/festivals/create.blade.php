<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Nieuw festival') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('planner.festivals.store') }}" class="space-y-6">
                    @csrf
                    @include('planner.festivals._form')
                    <div class="flex gap-4">
                        <x-primary-button>{{ __('Opslaan') }}</x-primary-button>
                        <a href="{{ route('planner.festivals.index') }}" class="text-sm text-gray-600 hover:text-gray-900 self-center">Annuleren</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
