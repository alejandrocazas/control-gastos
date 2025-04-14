<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Gastos</title>
    <!-- Incluir Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilos personalizados */
        .hero-section {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        .cta-buttons .btn {
            margin: 10px;
            padding: 10px 30px;
            font-size: 1.2rem;
        }
        .features-section {
            padding: 60px 0;
            background: #f8f9fa;
        }
        .feature-icon {
            font-size: 3rem;
            color: #2575fc;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Sección Hero -->
    <div class="hero-section">
        <div class="container">
            <h1 class="display-4">Bienvenido a Control de Gastos</h1>
            <p class="lead">Una herramienta sencilla para gestionar tus gastos de manera eficiente.</p>
            <div class="cta-buttons">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-light btn-lg">Iniciar Sesión</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">Registrarse</a>
                @else
                    <a href="{{ route('home') }}" class="btn btn-light btn-lg">Ir al Panel</a>
                @endguest
            </div>
        </div>
    </div>

    <!-- Sección de Características -->
    <div class="features-section">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4">
                    <div class="feature-icon">📊</div>
                    <h3>Gestión de Gastos</h3>
                    <p>Registra y categoriza tus gastos de manera sencilla.</p>
                </div>
                <div class="col-md-4">
                    <div class="feature-icon">📅</div>
                    <h3>Reportes Mensuales</h3>
                    <p>Genera reportes detallados de tus gastos mensuales.</p>
                </div>
                <div class="col-md-4">
                    <div class="feature-icon">🔒</div>
                    <h3>Seguro y Confiable</h3>
                    <p>Tus datos están protegidos con las mejores prácticas de seguridad.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4">
        <p>&copy; {{ date('Y') }} Control de Gastos. Todos los derechos reservados por Alejandro C.</p>
    </footer>

    <!-- Incluir Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>