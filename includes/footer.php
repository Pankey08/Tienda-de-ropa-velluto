<?php
declare(strict_types=1);
?>
<footer class="velluto-footer mt-5">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-md-4">
                <h5 class="footer-title">Velluto</h5>
                <p class="footer-text mb-0">
                    Ropa de hombre y mujer, elegante y contemporánea.
                </p>
            </div>

            <div class="col-md-4">
                <h5 class="footer-title">Navegación</h5>
                <ul class="list-unstyled footer-links">
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="catalogo.php">Catálogo</a></li>
                    <li><a href="catalogo.php?categoria=hombre">Hombre</a></li>
                    <li><a href="catalogo.php?categoria=mujer">Mujer</a></li>
                    <li><a href="carrito.php">Carrito</a></li>
                </ul>
            </div>

            <div class="col-md-4">
                <h5 class="footer-title">Enlaces de referencia</h5>
                <ul class="list-unstyled footer-links">
                    <li><a href="https://getbootstrap.com/" target="_blank" rel="noopener noreferrer">Bootstrap</a></li>
                    <li><a href="https://www.php.net/" target="_blank" rel="noopener noreferrer">PHP</a></li>
                    <li><a href="#top">Volver arriba</a></li>
                </ul>
            </div>
        </div>

        <hr class="footer-divider">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <p class="mb-0 footer-copy">
                &copy; <?= date('Y') ?> Velluto. Todos los derechos reservados.
            </p>
            <p class="mb-0 footer-copy">
                Modulo V ESARROLLO DE APLICACIONES DE COMERCIO ELECTRÓNICO.
            </p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>
