<x-layouts.app title="Reset Password">

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan Profil /</span> Reset Password</h4>

    <div class="row">
        <div class="col-md-12">

            {{-- Menu --}}
            <ul class="nav nav-pills flex-column flex-md-row mb-3">
                <li class="nav-item">
                    <a class="nav-link @if(request()->is('profil'))active @endif" href="{{ route('profile.index') }}"><i class="bx bx-user me-1"></i> Profil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->is('profil/reset-password'))active @endif" href="#"><i class="bx bx-bell me-1"></i> Reset Password</a>
                </li>
            </ul>

            {{-- Update Password --}}
            <div class="card mb-4">
                <h5 class="card-header">Update Password</h5>
                <div class="card-body">

                    {{-- Alert Password --}}
                    <div class="alert alert-warning">
                        <h6 class="alert-heading fw-bold mb-1">Petunjuk Keamanan Password!</h6>
                        <p class="mb-0">Pastikan password mudah diingat dan update secara berkala.</p>
                    </div>

                    {{-- Form Update Password --}}
                    <form action="{{ route('profile.updatePassword', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Input Password Lama --}}
                        <div class="row">
                            <div class="mb-3 col-md-12">
                                <x-input-password title="Password Lama" name="old_password" />
                            </div>
                        </div>

                        <div class="row">

                            {{-- Input Password Baru --}}
                            <div class="mb-3 col-md-6">
                                <x-input-password title="Password Baru" name="password" />
                            </div>

                            {{-- Input Konfirmasi Password Baru --}}
                            <div class="mb-3 col-md-6">
                                <x-input-password title="Konfirmasi Password" name="confirmation_password" />
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

</x-layouts.app>
