<x-layouts.app title="Profil">

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan Profil /</span> Profil</h4>

    <div class="row">
        <div class="col-md-12">

            {{-- Menu --}}
            <ul class="nav nav-pills flex-column flex-md-row mb-3">
                <li class="nav-item">
                    {{-- Jika request url adalah url yg di tentukan, set class active --}}
                    <a class="nav-link @if(request()->is('profil'))active @endif" href="#"><i class="bx bx-user me-1"></i> Profil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->is('profil/password-reset'))active @endif" href="{{ route('profile.indexResetPassword') }}">
                        <i class="bx bx-lock-alt me-1"></i> Reset Password</a>
                </li>
            </ul>

            <div class="card mb-4">

                {{-- Update Profile --}}
                <h5 class="card-header">Informasi Profil</h5>
                <div class="card-body">


                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-12">
                                        {{-- Foto Profile --}}
                                        <img src="{{ asset('storage/'. Auth()->user()->foto) }}" alt="user-avatar" class="d-block rounded" style="max-height: 120px" id="imagePreview" />
                                    </div>

                                    <div class="col-sm-12 col-md-auto mt-3">
                                        {{-- Form Upload New Photo --}}
                                        <form action="{{ route('profile.updateImage', $user->id) }}" method="POST" enctype="multipart/form-data"> {{-- Mengirim parameter user id pada route --}}
                                            @csrf
                                            @method('PUT')

                                            <x-partials.input-file name="foto" id="imageUpload" accept="image/png, image/jpeg, image/jpg" />
                                            <x-partials.error-message name="foto" />

                                            <button class="btn btn-primary mt-2" type="submit">Upload Foto Profil</button>
                                            <a class="btn btn-outline-secondary mt-2" href="{{ route('profile.index') }}">Reset</a>

                                            <p class="text-muted mt-2">File berupa JPG, JPEG, PNG. Maksimal 1 MB</p>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mt-5 mt-sm-5 mt-md-0 mt-lg-0">
                                <div class="row">
                                    <div class="col-12">
                                        {{-- TTD --}}
                                        <img src="{{ asset('storage/'. Auth()->user()->ttd) }}" alt="user-avatar" class="d-block rounded" style="max-height: 120px" id="ttdPreview" />
                                    </div>

                                    <div class="col-sm-12 col-md-auto mt-3">
                                        {{-- Form Upload New Photo --}}
                                        <form action="{{ route('profile.updateTtd', $user->id) }}" method="POST" enctype="multipart/form-data"> {{-- Mengirim parameter user id pada route --}}
                                            @csrf
                                            @method('PUT')

                                            <x-partials.input-file name="ttd" id="ttdUpload" accept="image/png, image/jpeg, image/jpg" />
                                            <x-partials.error-message name="ttd" />

                                            <button class="btn btn-primary mt-2" type="submit">Upload Tanda Tangan</button>
                                            <a class="btn btn-outline-secondary mt-2" href="{{ route('profile.index') }}">Reset</a>

                                            <p class="text-muted mt-2">File berupa JPG, JPEG, PNG. Maksimal 1 MB</p>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-0" />

                <div class="card-body">

                    {{-- Form Update Profile --}}
                    <form action="{{ route('profile.updateProfil', $user->id) }}" method="POST"> {{-- Mengirim parameter user id pada route --}}
                        @csrf
                        @method('PUT')
                        <div class="row">

                            {{-- Input Role --}}
                            <div class="mb-3 col-md-12">
                                <x-partials.label title="Role" />
                                <x-partials.input-text name="regional" :value="$user->role_name" readonly />
                            </div>

                            {{-- Input Nama --}}
                            <div class="mb-3 col-md-6">
                                {{-- old(NAMA_REQUEST) mengembalikan inputan sebelumnya --}}
                                <x-partials.label title="Nama Lengkap"/>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text" @error('name') style="border: solid red 1px;" @enderror><i class="bx bx-user"></i></span>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nama Lengkap" value="{{ old('name') ?? $user->name }}"/>
                                </div>
                                <x-partials.error-message name="name" class="d-block" />
                            </div>

                            {{-- Input Email --}}
                            <div class="mb-3 col-md-6">
                                <x-partials.label title="Email" />
                                <x-partials.input-text name="regional" :value="$user->role_name" readonly />
                            </div>

                            {{-- Input Nomor Telepon --}}
                            <div class="mb-3 col-md-6">
                                <x-partials.label title="Nomor Telepon"/>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text" @error('telp') style="border: solid red 1px;" @enderror><i class="bx bx-phone"></i></span>
                                    <input type="text" name="telp" class="form-control phone-mask  @error('telp') is-invalid @enderror" placeholder="0819 3456 1882" onkeypress="return event.charCode &gt;= 48 &amp;&amp; event.charCode &lt;= 57" maxlength="15" value="{{ old('telp') ?? $user->telp  }}"/>
                                </div>
                                <x-partials.error-message name="telp" class="d-block" />
                            </div>

                            {{-- Input Nik --}}
                            <div class="mb-3 col-md-6">
                                <x-partials.label title="NIK" />
                                <x-partials.input-text name="regional" :value="$user->nik" readonly />
                            </div>

                            {{-- Input Regional --}}
                            <div class="mb-3 col-md-6">
                                <x-partials.label title="Regional" />
                                <x-partials.input-text name="regional" :value="$user->namaRegional" readonly />
                            </div>

                            {{-- Input Lokasi --}}
                            <div class="mb-3 col-md-6">
                                <x-partials.label title="Lokasi" />
                                <x-partials.input-text name="lokasi" :value="$user->bandara.' | '.$user->lokasi " readonly />
                            </div>

                            {{-- Input Alamat KTP --}}
                            <div class="mb-3 col-md-6">
                                <x-partials.label title="Alamat KTP" required/>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text" @error('alamat_ktp') style="border: solid red 1px;" @enderror><i class="bx bx-location-plus"></i></span>
                                    <textarea name="alamat_ktp" class="form-control  @error('alamat_ktp') is-invalid @enderror">{{ old('alamat_ktp') ?? $user->alamat_ktp }}</textarea>
                                </div>
                                <x-partials.error-message name="alamat_ktp" class="d-block" />
                            </div>

                            {{-- Input Alamat Domisili --}}
                            <div class="mb-3 col-md-6">
                                <x-partials.label title="Alamat Domisili" />
                                <textarea name="alamat_dom" class="form-control @error('alamat_dom') is-invalid @enderror" readonly>{{ old('alamat_dom') ?? $user->alamat_dom }}</textarea>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <x-slot name="script">
        <script>
            $(document).ready(() => {
                $("#imageUpload").change(function () {
                    const file = this.files[0];
                    console.log(file);
                    if (file) {
                        let reader = new FileReader();
                        reader.onload = function (event) {
                            $("#imagePreview").attr("src", event.target.result);
                        };
                        reader.readAsDataURL(file);
                    }
                });

                $("#ttdUpload").change(function () {
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
