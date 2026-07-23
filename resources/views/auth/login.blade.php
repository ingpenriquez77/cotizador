<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión | Cotizador</title>

    <!-- Google Font: Inter / Poppins para esa tipografía limpia -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Bootstrap 5 CDN CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f3f4f6;
            color: #374151;
        }

        .login-card {
            background: #ffffff;
            border-radius: 12px;
            border: none;
            border-top: 4px solid #8b5cf6; /* Línea de acento morada */
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
            width: 100%;
            max-width: 440px;
        }

        .form-control {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.95rem;
            color: #1f2937;
            transition: all 0.2s ease-in-out;
        }

        .form-control:focus {
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
        }

        .form-label {
            font-weight: 500;
            font-size: 0.9rem;
            color: #4b5563;
            margin-bottom: 6px;
        }

        .btn-purple {
            background-color: #8b5cf6;
            border-color: #8b5cf6;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            padding: 10px 20px;
            border-radius: 8px;
            text-transform: uppercase;
            transition: background-color 0.2s;
        }

        .btn-purple:hover {
            background-color: #7c3aed;
            border-color: #7c3aed;
            color: #ffffff;
        }

        .forgot-link {
            color: #6b7280;
            text-decoration: underline;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .forgot-link:hover {
            color: #8b5cf6;
        }

        .logo-icon {
            width: 60px;
            height: 60px;
            background: #ffffff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            margin: 0 auto 24px auto;
            border: 1px solid #e5e7eb;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 p-3">

    <div class="text-center w-100" style="max-width: 440px;">

        <!-- Ícono estilizado como Logo -->
        <div class="logo-icon">
            <i class="bi bi-calculator-fill fs-2 text-primary" style="color: #8b5cf6 !important;"></i>
        </div>

        <!-- Tarjeta del Formulario -->
        <div class="card login-card p-4 p-sm-5 mx-auto">

            <!-- Alertas de error (Con verificación segura) -->
            @if (isset($errors) && $errors->any())
                <div class="alert alert-danger p-2 small mb-4 text-start rounded-3" role="alert">
                    <i class="bi bi-exclamation-circle me-1"></i> {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <!-- Campo Usuario / Email -->
                <div class="text-start mb-3">
                    <label for="email" class="form-label">Usuario</label>
                    <input type="email" name="email" id="email"
                           class="form-control {{ (isset($errors) && $errors->has('email')) ? 'is-invalid' : '' }}"
                           placeholder="Ingresa tu usuario"
                           value="{{ old('email') }}" required autofocus autocomplete="username">
                </div>

                <!-- Campo Contraseña -->
                <div class="text-start mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" name="password" id="password"
                           class="form-control {{ (isset($errors) && $errors->has('password')) ? 'is-invalid' : '' }}"
                           placeholder="••••••••" required autocomplete="current-password">
                </div>

                <!-- Recordarme -->
                <div class="form-check text-start mb-4">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                    <label class="form-check-label small text-muted" for="remember_me">
                        Recordarme
                    </label>
                </div>

                <!-- Botón e Isotipo -->
                <div class="d-flex align-items-center justify-content-between pt-2">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">¿Olvidaste tu contraseña?</a>
                    @else
                        <a href="#" class="forgot-link">¿Olvidaste tu contraseña?</a>
                    @endif
                    <button type="submit" class="btn btn-purple px-4">
                        INICIAR SESIÓN
                    </button>
                </div>
            </form>
        </div>

        <!-- Pie de página -->
        <div class="mt-4 text-uppercase small tracking-wider text-muted fw-semibold" style="letter-spacing: 1px; font-size: 0.75rem;">
            SISTEMA DE COTIZACIONES
        </div>

    </div>

    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
