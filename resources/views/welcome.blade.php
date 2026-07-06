<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900">
        <div class="min-h-screen flex flex-col">
            <header class="bg-white border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
                    <span class="text-xl font-bold text-indigo-600">FTS</span>
                    @if (Route::has('login'))
                        <nav class="flex gap-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm text-gray-600 hover:text-indigo-600">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-indigo-600">Inloggen</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-md">Registreren</a>
                                @endif
                            @endauth
                        </nav>
                    @endif
                </div>
            </header>

            <main class="flex-1 flex items-center">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                    <div class="max-w-2xl">
                        <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">
                            Festival Travel System
                        </h1>
                        <p class="mt-6 text-lg text-gray-600">
                            Boek busreizen naar festivals door heel Europa. Verdien punten bij elke reis en wissel ze in voor korting of VIP-toegang.
                        </p>
                        <div class="mt-10 flex gap-4">
                            @guest
                                <a href="{{ route('register') }}" class="rounded-md bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                    Account aanmaken
                                </a>
                                <a href="{{ route('login') }}" class="rounded-md bg-white px-5 py-3 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                    Inloggen
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}" class="rounded-md bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                    Naar dashboard
                                </a>
                            @endguest
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>
