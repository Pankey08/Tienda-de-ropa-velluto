<?php
declare(strict_types=1);

require_once __DIR__ . '/functions.php';

$cartCount = getCartItemCount();
$isLoggedIn = isUserLoggedIn();
$currentUser = getAuthenticatedUser();
?>
<nav class="navbar navbar-expand-lg navbar-light velluto-navbar sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand brand-logo fw-semibold" href="index.php">Velluto</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
            aria-controls="mainNavbar" aria-expanded="false" aria-label="Abrir navegación">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Inicio</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="catalogo.php">Catálogo</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="catalogo.php?categoria=hombre">Hombre</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="catalogo.php?categoria=mujer">Mujer</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link position-relative" href="carrito.php">
                        Carrito
                        <?php if ($cartCount > 0): ?>
                            <span class="cart-badge"><?= (int) $cartCount ?></span>
                        <?php endif; ?>
                    </a>
                </li>

                <?php if ($isLoggedIn && $currentUser !== null): ?>
                    <li class="nav-item">
                        <span class="nav-link user-greeting">
                            Hola, <?= htmlspecialchars(explode(' ', $currentUser['nombre_completo'])[0]) ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="btn velluto-btn-outline ms-lg-2" href="logout.php">Cerrar sesión</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="btn velluto-btn ms-lg-2" href="login.php">Iniciar sesión</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
