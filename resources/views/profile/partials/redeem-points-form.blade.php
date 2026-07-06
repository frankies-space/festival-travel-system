<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">{{ __('Punten inwisselen') }}</h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Wissel punten in voor korting op je volgende boeking of VIP-toegang.') }}
        </p>
    </header>

    @if ($user->available_discount > 0)
        <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-md text-sm text-green-700">
            {{ __('Je hebt € :amount korting beschikbaar voor je volgende boeking.', ['amount' => number_format($user->available_discount, 2, ',', '.')]) }}
        </div>
    @endif

    <div class="mt-6 grid gap-6 sm:grid-cols-2">
        <div class="border border-gray-200 rounded-lg p-4">
            <h3 class="font-medium text-gray-900">{{ __('Korting') }}</h3>
            <p class="mt-1 text-sm text-gray-600">
                {{ __(':rate punten = €1 korting. Minimum :min punten.', [
                    'rate' => config('fts.points_per_euro_discount'),
                    'min' => config('fts.min_redeem_discount_points'),
                ]) }}
            </p>
            <form method="POST" action="{{ route('points.redeem-discount') }}" class="mt-4 space-y-3">
                @csrf
                <div>
                    <x-input-label for="points" :value="__('Aantal punten')" />
                    <x-text-input id="points" name="points" type="number"
                        class="mt-1 block w-full"
                        min="{{ config('fts.min_redeem_discount_points') }}"
                        max="{{ $user->points_balance }}"
                        step="{{ config('fts.points_per_euro_discount') }}"
                        :value="old('points', config('fts.min_redeem_discount_points'))"
                        required />
                    <x-input-error class="mt-2" :messages="$errors->get('points')" />
                </div>
                <x-primary-button>{{ __('Inwisselen voor korting') }}</x-primary-button>
            </form>
        </div>

        <div class="border border-gray-200 rounded-lg p-4">
            <h3 class="font-medium text-gray-900">{{ __('VIP-toegang') }}</h3>
            <p class="mt-1 text-sm text-gray-600">
                {{ __(':cost punten voor :days dagen VIP-toegang (voorrang bij busplaatsing).', [
                    'cost' => config('fts.vip_redeem_cost'),
                    'days' => config('fts.vip_redeem_days'),
                ]) }}
            </p>
            @if ($user->isVip())
                <p class="mt-4 text-sm text-amber-600 font-medium">
                    @if ($user->vip_until?->isFuture())
                        {{ __('VIP actief tot :date', ['date' => $user->vip_until->format('d-m-Y')]) }}
                    @else
                        {{ __('Je hebt VIP-status via je puntensaldo.') }}
                    @endif
                </p>
            @elseif ($user->canRedeemVip())
                <form method="POST" action="{{ route('points.redeem-vip') }}" class="mt-4">
                    @csrf
                    <x-primary-button>{{ __('Inwisselen voor VIP') }}</x-primary-button>
                </form>
            @else
                <p class="mt-4 text-sm text-gray-500">
                    {{ __('Je hebt minimaal :cost punten nodig.', ['cost' => config('fts.vip_redeem_cost')]) }}
                </p>
            @endif
            <x-input-error class="mt-2" :messages="$errors->get('vip')" />
        </div>
    </div>

    @if ($user->pointsTransactions->isNotEmpty())
        <div class="mt-8">
            <h3 class="text-sm font-medium text-gray-900">{{ __('Puntengeschiedenis') }}</h3>
            <div class="mt-3 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left font-medium text-gray-500">{{ __('Datum') }}</th>
                            <th class="px-3 py-2 text-left font-medium text-gray-500">{{ __('Omschrijving') }}</th>
                            <th class="px-3 py-2 text-right font-medium text-gray-500">{{ __('Punten') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($user->pointsTransactions as $transaction)
                            <tr>
                                <td class="px-3 py-2">{{ $transaction->created_at->format('d-m-Y') }}</td>
                                <td class="px-3 py-2">{{ $transaction->description }}</td>
                                <td class="px-3 py-2 text-right {{ $transaction->amount > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->amount > 0 ? '+' : '' }}{{ $transaction->amount }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</section>
