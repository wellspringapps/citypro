<?php
use function Laravel\Folio\{
    name
};

name('admin.listing');
?>

<x-layouts.portal main-class-override="!p-0">
    <livewire:listing-editor :listing="$listing"/>
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/@googlemaps/extended-component-library/0.6.11/index.min.js"></script>
</x-layouts.portal>