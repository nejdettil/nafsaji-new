<x-filament::page>
    <form wire:submit.prevent="save">
        {{ $this->form }}
        
        <div class="flex items-center justify-end mt-4 gap-3">
            @foreach ($this->getFormActions() as $action)
                {{ $action }}
            @endforeach
        </div>
    </form>
</x-filament::page>
