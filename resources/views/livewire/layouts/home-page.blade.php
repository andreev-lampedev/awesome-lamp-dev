<div class="relative">
    <div class="flex flex-col container mx-auto pb-8">
        @include('livewire.partials.search')

        <!-- Flexes the left/right column of the whole page layout -->
        <div class="flex flex-col sm:flex-row justify-around sm:mt-8">
            @include('livewire.partials.package-list-tags')

            <!-- The body -->
            @yield('home-page-body')
        </div>
    </div>
    <div wire:loading style="top: 7em; width: 10em; left: 50%; margin-left: -5em" class="absolute bg-indigo-900 rounded-lg text-4xl text-center text-white tracking-wide py-6 w-full uppercase w-4">Loading...</div>
</div>
