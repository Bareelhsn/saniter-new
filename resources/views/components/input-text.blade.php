@props(['title' => 'Title', 'name' => 'Name', 'margin' => false])

<div @if ($margin) class="{{ $margin }}" @endif>
    <x-partials.label :title="$title" />
    <x-partials.input-text :name="$name" {{ $attributes->merge() }}/>
    <x-partials.error-message :name="$name" />
</div>
