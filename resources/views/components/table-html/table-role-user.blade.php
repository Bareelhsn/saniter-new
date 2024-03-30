<table class="table table-bordered mt-4">
    <thead>
        <th>Menu</th>
        <th>Permissions</th>
    </thead>
    <tbody>

        @php $n=0; @endphp
        @foreach ($permissionsAll as $key => $items)
        <tr>
            <td>
                @if (count($user) > 0 && $userRole == $key)
                    <div class="form-check form-check-inline mt-3">
                        <input class="form-check-input {{ $key }}-All" type="checkbox" id="checkBox{{ $key }}" data-judul="{{ $key }}" onchange="checkAll(this)" @if(getCheckedUserMenu($user, $key) > 0) checked @endif>
                        <label class="form-check-label" for="inlineCheckbox1">{{ $key }}</label>
                    </div>
                @else
                    <div class="form-check form-check-inline mt-3">
                        <input class="form-check-input {{ $key }}-All" type="checkbox" id="checkBox{{ $key }}" data-judul="{{ $key }}" onchange="checkAll(this)" @if(getCheckedMenu($role->permissions, $key) > 0) checked @endif>
                        <label class="form-check-label" for="inlineCheckbox1">{{ $key }}</label>
                    </div>
                @endif
            </td>
            <td>
                @php $n = 0; @endphp
                @foreach ($items as $item)

                    @php $print = true; @endphp

                        @if (count($user) > 0 && $user[$n] == $item->name)
                            @for ($i = 0; $i < count($user); $i++)
                                @if ($user[$i] === $item->name)
                                    <div class="form-check form-check-inline mt-3">
                                        <input class="form-check-input {{ $key }}" type="checkbox" value="{{ $item->name }}" name="checkBox[]" data-judul="{{ $key }}" onchange="checkJudul(this)" checked>
                                        <label class="form-check-label" for="inlineCheckbox1">{{ $item->name }}</label>
                                    </div>
                                    @php $print = false; @endphp
                                @endif
                            @endfor
                        @else
                            @for ($i = 0; $i < count($role->permissions); $i++)
                                @if ($role->permissions[$i]->name === $item->name)
                                    <div class="form-check form-check-inline mt-3">
                                        <input class="form-check-input {{ $key }}" type="checkbox" value="{{ $item->name }}" name="checkBox[]" data-judul="{{ $key }}" onchange="checkJudul(this)" checked>
                                        <label class="form-check-label" for="inlineCheckbox1">{{ $item->name }}</label>
                                    </div>
                                    @php $print = false; @endphp
                                @endif
                            @endfor
                        @endif

                @if ($print == true)
                    <div class="form-check form-check-inline mt-3">
                        <input class="form-check-input {{ $key }}" type="checkbox" value="{{ $item->name }}" name="checkBox[]" data-judul="{{ $key }}" onchange="checkJudul(this)" >
                        <label class="form-check-label" for="inlineCheckbox1">{{ $item->name }}</label>
                    </div>
                @endif

                @php $n++; @endphp
                @endforeach
            </td>
        </tr>
        @php $n++; @endphp
        @endforeach
    </tbody>
</table>
