<x-layouts.auth.app title="Reset Password">

    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
            <div class="card">
                <div class="card-body">

                    {{-- Logo --}}
                    <div class="app-brand justify-content-center">
                        <img src="{{ asset('assets/img/logo/logo-qinar.png') }}" alt="Logo Qinar" width="100px">
                        <h4 class="mb-2">PT. Qinar Raya Mandiri</h4>
                    </div>

                    {{-- Header text --}}
                    <h4 class="mb-2 mt-0 d-flex justify-content-center">Reset Password</h4>
                    <p class="mb-4 d-flex justify-content-center">Masukkan email akun dan password baru.</p>

                    {{-- Form --}}
                    <form class="mb-3" action="{{ route('password.update') }}" method="POST">
                        @csrf

                        <input type="hidden" name="token" value="{{ request()->route('token') }}">

                        <x-input-text title="Email" name="email" placeholder="Masukkan email" margin="mb-3" />

                        <x-input-password title="Password Baru" name="password" placeholder="Masukkan password baru" margin="mb-3" />

                        <x-input-password title="Konfirmasi Password Baru" name="password_confirmation" placeholder="Ketik ulang password" margin="mb-3" />

                        {{-- Submit Button --}}
                        <button class="btn btn-primary d-grid w-100">Reset Password</button>
                    </form>

                    {{-- Back To Login --}}
                    <div class="text-center">
                        <a href="{{ route('login') }}" class="d-flex align-items-center justify-content-center">
                            <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                            Kembali ke login
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

</x-layouts.auth.app>
