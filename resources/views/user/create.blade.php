<x-layouts.app title="Tambah User">
    <div class="card mb-12">
        <div class="card-header">
            <h5 class="mb-0">Form User Baru</h5>
        </div>
        <form method="post" action="{{ route('user.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">

                {{-- Nama Lengkap --}}
                <div class="mb-3">
                    <x-partials.label title="Nama Lengkap" required/>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text" @error('name') style="border: solid red 1px;" @enderror><i class="bx bx-user"></i></span>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nama Lengkap" value="{{ old('name') }}"/>
                    </div>
                    <x-partials.error-message name="name" class="d-block" />
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <x-partials.label title="Email" required/>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text" @error('email') style="border: solid red 1px;" @enderror><i class="bx bx-envelope"></i></span>
                        <input type="text" name="email" class="form-control  @error('email') is-invalid @enderror" placeholder="email@gmail.com" value="{{ old('email') }}"/>
                    </div>
                    <x-partials.error-message name="email" class="d-block" />
                </div>

                {{-- NIK --}}
                <div class="mb-3">
                    <x-partials.label title="NIK" required/>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text" @error('nik') style="border: solid red 1px;" @enderror><i class="bx bx-id-card"></i></span>
                        <input type="text" name="nik" class="form-control  @error('nik') is-invalid @enderror" placeholder="NIK" onkeypress="return event.charCode &gt;= 48 &amp;&amp; event.charCode &lt;= 57" maxlength="20" value="{{ old('nik') }}"/>
                    </div>
                    <x-partials.error-message name="nik" class="d-block" />
                </div>

                {{-- Alamat KTP --}}
                <div class="mb-3">
                    <x-partials.label title="Alamat KTP" required/>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text" @error('alamat_ktp') style="border: solid red 1px;" @enderror><i class="bx bx-location-plus"></i></span>
                        <textarea name="alamat_ktp" class="form-control  @error('alamat_ktp') is-invalid @enderror">{{ old('alamat_ktp') }}</textarea>
                    </div>
                    <x-partials.error-message name="alamat_ktp" class="d-block" />
                </div>

                {{-- Alamat Domisili --}}
                <div class="mb-3">
                    <x-partials.label title="Alamat Domisili" required/>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text" @error('alamat_dom') style="border: solid red 1px;" @enderror><i class="bx bx-location-plus"></i></span>
                        <textarea name="alamat_dom" class="form-control @error('alamat_dom') is-invalid @enderror">{{ old('alamat_dom') }}</textarea>
                    </div>
                    <x-partials.error-message name="alamat_dom" class="d-block" />
                </div>

                {{-- Regional --}}
                <div class="mb-3">
                    <x-partials.label title="Regional Kerja" required/>
                    <select class="form-select  @error('regional_id') is-invalid @enderror" name="regional_id">
                        <option value="" disabled selected>Pilih Regional Kerja</option>
                        @foreach ($regional as $r)
                            <option @if(old('regional_id') == $r->id) selected @endif value="{{ $r->id }}"> {{ $r->nama }}</option>
                        @endforeach
                    </select>
                    <x-partials.error-message name="regional_id" class="d-block" />
                </div>

                {{-- Lokasi --}}
                <div class="mb-3">
                    <x-partials.label title="Lokasi Kerja" required/>
                    <select class="form-select  @error('lokasi_id') is-invalid @enderror" name="lokasi_id">
                        <option value="" disabled selected>Pilih Lokasi Kerja</option>
                        @foreach ($lokasi as $l)
                            <option @if(old('lokasi_id') == $l->id) selected @endif value="{{ $l->id }}"> {{ $l->nama_bandara }} | {{ $l->lokasi_proyek }}</option>
                        @endforeach
                    </select>
                    <x-partials.error-message name="lokasi_id" class="d-block" />
                </div>

                {{-- Role --}}
                <div class="mb-3">
                    <x-partials.label title="Role" required/>
                    <select class="form-select  @error('role_id') is-invalid @enderror" name="role_id">
                        <option value="" disabled selected>Pilih Role</option>
                        @foreach ($roles as $rl)
                            <option @if(old('role_id') == $rl->id) selected @endif value="{{ $rl->id }}"> {{ $rl->name }}</option>
                        @endforeach
                    </select>
                    <x-partials.error-message name="role_id" class="d-block" />
                </div>

                {{-- Nomor Telepon --}}
                <div class="mb-3">
                    <x-partials.label title="Nomor Telepon" required/>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text" @error('telp') style="border: solid red 1px;" @enderror><i class="bx bx-phone"></i></span>
                        <input type="text" name="telp" class="form-control phone-mask  @error('telp') is-invalid @enderror" placeholder="0819 3456 1882" onkeypress="return event.charCode &gt;= 48 &amp;&amp; event.charCode &lt;= 57" maxlength="15" value="{{ old('telp') }}"/>
                    </div>
                    <x-partials.error-message name="telp" class="d-block" />
                </div>

                {{-- Foto --}}
                <div class="mb-3">
                    <x-partials.label title="Foto" />
                    <div class="">
                        <div class="col-auto mb-3">
                            {{-- Foto Preview --}}
                            <img src="{{ asset('storage/user-images/default.jpg') }}" alt="user-avatar" class="d-block rounded img-fluid" style="max-height: 200px" id="fotoPreview" />
                        </div>

                        <input class="form-control  @error('foto') is-invalid @enderror" type="file" id="foto" name="foto" accept="image/png, image/jpeg, image/jpg" />
                    </div>
                    <x-partials.error-message name="foto" class="d-block" />
                </div>

                {{-- TTD --}}
                <div class="mb-3">
                    <x-partials.label title="Tanda Tangan" />
                    <div class="">
                        <div class="col-auto mb-3">
                            {{-- TTD Prview --}}
                            <img src="{{ asset('storage/user-ttd/default.jpg') }}" alt="user-avatar" class="d-block rounded img-fluid" style="max-height: 200px" id="ttdPreview" />
                        </div>

                        <input class="form-control  @error('ttd') is-invalid @enderror" type="file" id="ttd" name="ttd" accept="image/png, image/jpeg, image/jpg" />
                    </div>
                    <x-partials.error-message name="tdd" class="d-block" />
                </div>

                {{-- Password --}}
                {{-- <div class="mb-3">
                    <x-partials.label title="Password" required/>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text" @error('password') style="border: solid red 1px;" @enderror><i class="bx bx-key"></i></span>
                        <input type="password" name="password" class="form-control password  @error('password') is-invalid @enderror" />
                    </div>
                    <x-partials.error-message name="password" class="d-block" />
                </div> --}}

                {{-- Confirm Password --}}
                {{-- <div class="">
                    <x-partials.label title="Konfirmasi Password" required/>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text" @error('confirmation_password') style="border: solid red 1px;" @enderror><i class="bx bx-key"></i></span>
                        <input type="password" name="confirmation_password" class="form-control confirm-password  @error('confirmation_password') is-invalid @enderror" />
                    </div>
                    <x-partials.error-message name="confirmation_password" class="d-block" />
                </div> --}}
            </div>
            <div class="card-footer">
                <a href="{{ route('user.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>

    <x-slot name="script">
        <script>
            $(document).ready(() => {
                $("#foto").change(function () {
                    const file = this.files[0];
                    console.log(file);
                    if (file) {
                        let reader = new FileReader();
                        reader.onload = function (event) {
                            $("#fotoPreview").attr("src", event.target.result);
                        };
                        reader.readAsDataURL(file);
                    }
                });

                $("#ttd").change(function () {
                    const file = this.files[0];
                    console.log(file);
                    if (file) {
                        let reader = new FileReader();
                        reader.onload = function (event) {
                            $("#ttdPreview").attr("src", event.target.result);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            });
        </script>
    </x-slot>
</x-layouts.app>
