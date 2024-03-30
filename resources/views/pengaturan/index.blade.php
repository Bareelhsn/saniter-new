<x-layouts.app title="Pengaturan">

    <h4 class="fw-bold py-3 mb-4">Pengaturan</h4>

    <div class="row mb-4">
        <div class="col-md-12">

            <div class="card">
                <h5 class="card-header">Manajemen Hak Akses</h5>
                <div class="card-body">

                    {{-- Menu --}}
                    <ul class="nav nav-pills flex-column flex-md-row mb-3">
                        <li class="nav-item me-2">
                            {{-- Jika request url adalah url yg di tentukan, set class active --}}
                            <a class="btn btn-primary" href="{{ route('pengaturan.role.index') }}" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" data-bs-original-title="<span>Hak akses.</span>">
                                <i class="bx bx-user-circle me-1"></i> Role
                            </a>
                        </li>
                        <li class="nav-item me-2">
                            {{-- Jika request url adalah url yg di tentukan, set class active --}}
                            <a class="btn btn-primary" href="{{ route('pengaturan.permission.index') }}" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" data-bs-original-title="<span>Izin hak akses.</span>">
                                <i class="bx bx-lock-alt me-1"></i> Permission
                            </a>
                        </li>
                        <li class="nav-item me-2">
                            {{-- Jika request url adalah url yg di tentukan, set class active --}}
                            <a class="btn btn-primary" href="{{ route('pengaturan.assign-role.index') }}" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" data-bs-original-title="<span>User Akses.</span>">
                                <i class="bx bx-key me-1"></i> Tetapkan Role
                            </a>
                        </li>
                    </ul>

                </div>
            </div>

        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">

            <div class="card">
                <h5 class="card-header">Manajemen Menu</h5>
                <div class="card-body">

                    {{-- Menu --}}
                    <ul class="nav nav-pills flex-column flex-md-row mb-3">
                        <li class="nav-item me-2">
                            {{-- Jika request url adalah url yg di tentukan, set class active --}}
                            <a class="btn btn-primary" href="{{ route('pengaturan.kategorimenu.index') }}">
                                <i class="bx bx-category me-1"></i> Kategori Menu
                            </a>
                        </li>
                        <li class="nav-item me-2">
                            {{-- Jika request url adalah url yg di tentukan, set class active --}}
                            <a class="btn btn-primary" href="{{ route('pengaturan.menu.index') }}">
                                <i class="bx bx-menu me-1"></i> Menu
                            </a>
                        </li>
                        <li class="nav-item me-2">
                            {{-- Jika request url adalah url yg di tentukan, set class active --}}
                            <a class="btn btn-primary" href="{{ route('pengaturan.submenu.index') }}">
                                <i class="bx bx-menu-alt-right me-1"></i> Sub Menu
                            </a>
                        </li>
                    </ul>

                </div>
            </div>

        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">

            <div class="card">
                <h5 class="card-header">Manajemen Sistem</h5>
                <div class="card-body">

                    {{-- Menu --}}
                    <ul class="nav nav-pills flex-column flex-md-row mb-3">
                        <li class="nav-item me-2">
                            {{-- Jika request url adalah url yg di tentukan, set class active --}}
                            <a class="btn btn-primary" href="{{ route('shift.index') }}">
                                <i class="bx bx-time me-1"></i> Shift
                            </a>
                        </li>
                        <li class="nav-item me-2">
                            {{-- Jika request url adalah url yg di tentukan, set class active --}}
                            <a class="btn btn-primary" href="{{ route('regional.index') }}">
                                <i class="bx bx-map-pin me-1"></i> Regional
                            </a>
                        </li>
                        <li class="nav-item me-2">
                            {{-- Jika request url adalah url yg di tentukan, set class active --}}
                            <a class="btn btn-primary" href="{{ route('pengaturan.submenu.index') }}">
                                <i class="bx bx-map me-1"></i> Lokasi
                            </a>
                        </li>
                    </ul>

                </div>
            </div>

        </div>
    </div>


</x-layouts.app>
