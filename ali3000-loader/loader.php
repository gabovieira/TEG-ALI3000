<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargando - ali3000 Consultores</title>
    <link rel="stylesheet" href="assets/css/loader.css">
</head>
<body>
    <div class="loader-container">
        <div class="loader-content">
            <!-- Logo Container -->
            <div class="logo-container">
                <div class="logo-wrapper" id="logoWrapper">
                    <!-- Animated rings -->
                    <div class="ring ring-1"></div>
                    <div class="ring ring-2"></div>
                    
                    <!-- Logo -->
                    <div class="logo-circle">
                        <img src="assets/images/logoali3000.png" alt="ali3000 consultores logo" class="logo-img">
                    </div>
                </div>
            </div>

            <!-- Company Name -->
            <div class="company-info">
                <h1 class="company-name">ali3000</h1>
                <p class="company-subtitle">CONSULTORES</p>
            </div>

            <!-- Loading Progress -->
            <div class="progress-section">
                <div class="progress-bar">
                    <div class="progress-fill" id="progressFill"></div>
                </div>

                <div class="progress-info">
                    <span>Cargando...</span>
                    <span class="progress-percent" id="progressPercent">0%</span>
                </div>
            </div>

            <!-- Loading Animation -->
            <div class="loading-dots" id="loadingDots">
                <div class="dot dot-1"></div>
                <div class="dot dot-2"></div>
                <div class="dot dot-3"></div>
            </div>

            <!-- Success Message -->
            <div class="success-message" id="successMessage" style="display: none;">
                <p>¡Listo para comenzar!</p>
            </div>
        </div>

        <!-- Background Pattern -->
        <div class="bg-pattern">
            <div class="pattern-circle circle-1"></div>
            <div class="pattern-circle circle-2"></div>
            <div class="pattern-circle circle-3"></div>
        </div>
    </div>

    <script src="assets/js/loader.js"></script>
</body>
</html>
