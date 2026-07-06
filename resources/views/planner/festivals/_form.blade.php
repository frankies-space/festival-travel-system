<div class="space-y-4">
    <div>
        <x-input-label for="name" :value="__('Naam')" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
            :value="old('name', $festival->name ?? '')" required />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>

    <div>
        <x-input-label for="location" :value="__('Locatie')" />
        <x-text-input id="location" name="location" type="text" class="mt-1 block w-full"
            :value="old('location', $festival->location ?? '')" required />
        <x-input-error class="mt-2" :messages="$errors->get('location')" />
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <x-input-label for="start_date" :value="__('Startdatum')" />
            <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full"
                :value="old('start_date', isset($festival) ? $festival->start_date->format('Y-m-d') : '')" required />
            <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
        </div>
        <div>
            <x-input-label for="end_date" :value="__('Einddatum')" />
            <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full"
                :value="old('end_date', isset($festival) ? $festival->end_date->format('Y-m-d') : '')" required />
            <x-input-error class="mt-2" :messages="$errors->get('end_date')" />
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <x-input-label for="max_capacity" :value="__('Max. capaciteit')" />
            <x-text-input id="max_capacity" name="max_capacity" type="number" class="mt-1 block w-full"
                :value="old('max_capacity', $festival->max_capacity ?? 50)" min="1" required />
            <x-input-error class="mt-2" :messages="$errors->get('max_capacity')" />
        </div>
        <div>
            <x-input-label for="ticket_price" :value="__('Ticketprijs (€)')" />
            <x-text-input id="ticket_price" name="ticket_price" type="number" step="0.01" class="mt-1 block w-full"
                :value="old('ticket_price', $festival->ticket_price ?? 149.00)" min="0" required />
            <x-input-error class="mt-2" :messages="$errors->get('ticket_price')" />
        </div>
    </div>

    <div>
        <x-input-label for="description" :value="__('Beschrijving')" />
        <textarea id="description" name="description" rows="4"
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $festival->description ?? '') }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('description')" />
    </div>
</div>
