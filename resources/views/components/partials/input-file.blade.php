<?php
    $class = 'form-control';

    // Jika input terdapat error validasi
    if ($errors->has($name)) {
        $class = 'form-control is-invalid';
    }
?>
<input type="file" {{ $attributes->merge(['class' => $class]) }}>

