<x-layouts.app title="Ubah Permission">

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan / Permissions /</span> Ubah Permissions</h4>

    <div class="row mb-4">
        <div class="col-md-12">

            <div class="card">
                <h5 class="card-header">Manajemen Hak Akses</h5>
                {{-- Form Tambah Menu --}}
                <form action="{{ route('pengaturan.permission.update', $permissions->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        {{-- Input Judul --}}
                        <x-input-text title="Permission" name="name" placeholder="Masukkan role" margin="mb-3" value="{{ $permissions->name ?? old('name') }}"/>


                        {{-- Input Permisson --}}
                        <x-partials.label title="Role"/>
                        <select id="choices-multiple-remove-button" name="roles[]" placeholder="Pilih role." multiple>

                            {{-- Perulangan select role --}}
                            @foreach ($role as $item)

                                {{-- Tetapkan variabel print ke true --}}
                                @php $print = true; @endphp

                                {{-- Perulangan role pada permission yg diedit --}}
                                @for ($i = 0; $i < count($permissions->roles); $i++)

                                    {{-- Jika role pada permission yg diedit sesuai dengan nama pada role --}}
                                    @if ($permissions->roles[$i]->name == $item->name)

                                        {{-- Print dan select role tersebut --}}
                                        <option selected value='{{ $item->name }}'>{{ $item->name }}</option>

                                        {{-- Ubah print ke false karna role ini sudah tampil dan sudah terselect otomatis --}}
                                        @php $print = false; @endphp
                                    @endif
                                @endfor

                                {{-- Jika role belum tampil maka print role tersebut dan jangan diselect--}}
                                @if ($print == true)
                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                @endif

                            @endforeach
                        </select>
                    </div>

                <div class="card-footer">
                    {{-- Button Submit --}}
                    <a href="{{ route('pengaturan.permission.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>

                </form>

            </div>
        </div>
    </div>

    <x-slot name="script">
        <script>
            $(document).ready(function () {
                var multipleCancelButton = new Choices('#choices-multiple-remove-button', {
                        removeItemButton: true,
                    });
            });
        </script>
    </x-slot>

</x-layouts.app>
