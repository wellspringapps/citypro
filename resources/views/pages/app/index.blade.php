<?php
use function Laravel\Folio\{name};

name('portal.index');
?>

<x-layouts.portal main-class-override="!p-0">
    @if (auth()->user()->listing)
        <livewire:listing-editor :listing="auth()->user()->listing" />
    @else
        <x-pricing />
    @endif

    <script
        type="module"
        src="https://ajax.googleapis.com/ajax/libs/@googlemaps/extended-component-library/0.6.11/index.min.js"
    ></script>
</x-layouts.portal>
