<?php
    $class = 'form-control';

    // Jika input terdapat error validasi
    if ($errors->has($name)) {
        $class = 'form-control is-invalid';
    }
?>
<input type="text" {{ $attributes->merge(['class' => $class]) }}>

