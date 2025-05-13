<x-filament::page>
    <form wire:submit.prevent="save">
        {{ $this->form }}

        <div class="mt-6 flex justify-end">
            <x-filament::button type="submit" wire:loading.attr="disabled">
                حفظ الإعدادات
            </x-filament::button>
        </div>
    </form>
</x-filament::page>
