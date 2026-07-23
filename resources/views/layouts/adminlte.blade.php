<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Cotizador POS')</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:ital,wght@0,300;0,400;0,700;1,400&display=fallback">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- OverlayScrollbars -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css">
    <!-- AdminLTE 4 CSS (cdnjs - Estable y sin bloqueos ORB) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/4.0.0-beta2/css/adminlte.min.css">
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">

        <!-- Header -->
        <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                            <i class="bi bi-list fs-4"></i>
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" data-bs-toggle="dropdown">
                            <span class="d-none d-md-inline fw-semibold">{{ Auth::user()->name ?? 'Usuario' }}</span>
                            {{-- Badge de Rol --}}
                            @if(Auth::user()->isAdmin())
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">Admin</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill">Lector</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger fw-medium">
                                        <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Sidebar -->
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <div class="sidebar-brand">
                <a href="{{ route('dashboard') }}" class="brand-link">
                    <span class="brand-text fw-bold" style="color: #c084fc;">Cotizador <span class="fw-light text-white">POS</span></span>
                </a>
            </div>
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">

                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-speedometer2"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        {{-- Menús de Administración (Solo Visibles para Admin) --}}
                        @if(Auth::user()->isAdmin())
                            <!-- Clientes -->
                            <li class="nav-item">
                                <a href="{{ route('clients.index') }}" class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-people"></i>
                                    <p>Clientes</p>
                                </a>
                            </li>

                            <!-- Productos -->
                            <li class="nav-item">
                                <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-box-seam"></i>
                                    <p>Productos</p>
                                </a>
                            </li>
                        @endif

                        <!-- Cotizaciones (Visible para Todos) -->
                        <li class="nav-item">
                            <a href="{{ route('quotes.index') }}" class="nav-link {{ request()->routeIs('quotes.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-file-earmark-text"></i>
                                <p>Cotizaciones</p>
                            </a>
                        </li>

                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="app-main">
            <div class="app-content-header py-3">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0 fw-bold">@yield('page_title', 'Panel Principal')</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="app-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="app-footer">
            <div class="float-end d-none d-sm-inline">v1.0</div>
            <strong>Cotizador POS &copy; 2026.</strong> Todos los derechos reservados.
        </footer>
    </div>

    <!-- Scripts CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"></script>
    <!-- AdminLTE 4 JS (cdnjs - Estable) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/4.0.0-beta2/js/adminlte.min.js"></script>
</body>
</html>
