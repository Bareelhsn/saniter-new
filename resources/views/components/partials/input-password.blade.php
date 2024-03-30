<?php
    $class = 'form-control';

    // Jika input terdapat error validasi
    if ($errors->has($name)) {
        $class = 'form-control is-invalid';
    }
?>

<input type="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" {{ $attributes->merge(['class' => $class]) }} />
<span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
