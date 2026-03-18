<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

http_response_code(404);

$pageTitle = 'Página no encontrada';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="info-box text-center">
                    <h1 class="section-title display-5 mb-3">404</h1>
                    <p class="section-text mx-auto mb-4">
                        La página que buscas no existe, fue movida o la dirección ingresada no es válida.
                    </p>

                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="index.php" class="btn velluto-btn">Ir al inicio</a>
                        <a href="catalogo.php" class="btn velluto-btn-outline">Ver catálogo</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
