<x-layouts.auth.app title="Login Page">

    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
            <div class="card">
                <div class="card-body">

                    {{-- Logo --}}
                    <div class="app-brand justify-content-center">
                        <img src="{{ asset('assets/img/logo/logo-qinar.png') }}" alt="Logo Qinar" width="100px">
                        <h4 class="mb-2">PT. Qinar Raya Mandiri</h4>
                    </div>

                    {{-- Header text --}}
                    <h4 class="mb-2 d-flex justify-content-center">Login</h4>
                    <p class="mb-4 d-flex justify-content-center">Silahkan masukkan akun anda.</p>

                    {{-- Form --}}
                    <form class="mb-3" action="{{ route('login') }}" method="POST">
                        @csrf

                        {{-- Input Email --}}
                        <div class="mb-3">
                            <x-partials.label title="Email" />
                            <x-partials.input-text name="email" placeholder="Masukkan email" />
                            <x-partials.error-message name="email" />
                        </div>

                        {{-- Input Password --}}
                        <div class="mb-3 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <x-partials.label title="Password" />
                                <a href="{{ route('password.request') }}">
                                    <small>Lupa Password?</small>
                                </a>
                            </div>
                            <div class="input-group input-group-merge">
                                <x-partials.input-password name="password" />
                                <x-partials.error-message name="password" />
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="mb-3">
                            <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</x-layouts.auth.app>
