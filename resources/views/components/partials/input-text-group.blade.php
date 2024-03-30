@php
    $class = 'form-control';

    // Jika input terdapat error validasi
    if ($errors->has($name)) {
        $class = 'form-control is-invalid';
    }
@endphp

<div class="input-group">
    {{-- Jika ada grup di depan --}}
    @if (($front ?? false))
        <span class="input-group-text">{{ $front }}</span>
    @endif

    <input type="text" {{ $attributes->merge(['class' => $class]) }}>
</div>
