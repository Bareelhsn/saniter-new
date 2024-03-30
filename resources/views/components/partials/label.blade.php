@props(['required' => false,'title'=> null])

<label {{ $attributes->merge(['class' => 'form-label']) }} for="{{ str_replace(' ','-',strtolower($title)) }}">
    {{ $title }}
    @if($required)
        <span style="color: red">*</span>
    @endif
</label>
