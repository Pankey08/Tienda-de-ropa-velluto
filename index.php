<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Inicio';

$stmt = $pdo->prepare(
    'SELECT id, nombre, categoria, precio, imagen, descripcion
     FROM productos
     WHERE activo = 1
     ORDER BY id DESC
     LIMIT 4'
);
$stmt->execute();
$featuredProducts = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$flash = getFlashMessage();
?>

<?php if ($flash !== null): ?>
    <div class="container mt-4">
        <div class="js-auto-hide <?= $flash['type'] === 'success' ? 'alert-custom-success' : 'alert-custom-danger' ?>">
            <?= escape($flash['message']) ?>
        </div>
    </div>
<?php endif; ?>

<div class="marquee-box">
    <marquee behavior="scroll" direction="left">
        Bienvenido a Velluto. Descubre Ropa para hombre y mujer, elegante y contemporáneo.
    </marquee>
</div>

<section class="hero-section">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <p class="text-uppercase small fw-semibold mb-2">Colección destacada</p>
                <h1 class="hero-title">Minimalismo que se viste todos los días</h1>
                <p class="hero-text mt-3">
                    Velluto ofrece ropa para hombre y mujer con diseño limpio, materiales cómodos y una propuesta visual elegante para cualquier ocasión.
                </p>

                <div class="d-flex flex-wrap gap-3 mt-4">
                    <a href="catalogo.php" class="btn velluto-btn">Ver catálogo</a>
                    <a href="#destacados" class="btn velluto-btn-outline">Ir a destacados</a>
                </div>

                <div class="mt-4">
                    <ul>
                        <li>Diseño minimalista y elegante</li>
                        <li>Playeras para hombre y mujer</li>
                        <li>Compra rápida y segura</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="velluto-card">
                    <img
                        src="assets/img/placeholders/hero-default.jpg"
                        data-hover-image="assets/img/placeholders/hero-hover.jpg"
                        alt="Colección principal Velluto"
                        class="velluto-card-img"
                    >
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5" id="destacados">
    <div class="container">
        <div class="mb-4">
            <h2 class="section-title">Productos destacados</h2>
            <p class="section-text">
                Conoce nuestro catalogo de ropa, las prendas más elegantes y finas a precios imbatibles.
            </p>
        </div>

        <div class="row g-4">
            <?php foreach ($featuredProducts as $product): ?>
                <div class="col-md-6 col-lg-3">
                    <div class="velluto-card h-100">
                        <img
                            src="<?= escape($product['imagen']) ?>"
                            alt="<?= escape($product['nombre']) ?>"
                            class="velluto-card-img"
                        >
                        <div class="velluto-card-body d-flex flex-column">
                            <h3 class="product-title"><?= escape($product['nombre']) ?></h3>
                            <p class="product-meta mb-2 text-capitalize">Categoría: <?= escape($product['categoria']) ?></p>
                            <p class="product-price mb-2"><?= formatPrice((float) $product['precio']) ?></p>
                            <p class="product-meta flex-grow-1">
                                <?= escape(mb_strimwidth($product['descripcion'], 0, 90, '...')) ?>
                            </p>
                            <a href="producto.php?id=<?= (int) $product['id'] ?>" class="btn velluto-btn mt-3">Ver producto</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4 align-items-start">
            <div class="col-lg-6">
                <div class="interactive-map p-3">
                    <h2 class="section-title h3">Explora nuestras categorías</h2>
                    <p class="section-text">
                        Descubre todos nuestros productos.
                    </p>

                    <img src="assets/img/placeholders/abanico-ropa.jpg" usemap="#categorias-map" >

                    <map name="categorias-map">
                        <area shape="rect" coords="0,0,250,400" href="catalogo.php?categoria=hombre" alt="Hombre">
                        <area shape="rect" coords="251,0,500,400" href="catalogo.php?categoria=mujer" alt="Mujer">
                    </map>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="interactive-map p-3">
                    <h2 class="section-title h3">Ropa de temporada</h2>
                    <p class="section-text">
                        Prendas para todas las estaciones y para cualquier ocasion.
                    </p>

                    <img src="assets/img/placeholders/ropa-temporada.jpg" usemap="#categorias-map">

                    <map name="categorias-map">
                        <area shape="rect" coords="0,0,250,400" href="catalogo.php?categoria=hombre" alt="Hombre">
                        <area shape="rect" coords="251,0,500,400" href="catalogo.php?categoria=mujer" alt="Mujer">
                    </map>
                </div>
            </div>

            
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container text-center">
        <h2 class="section-title">Descubre la esencia de Velluto</h2>
        <p class="section-text mx-auto">
            Nuestra propuesta combina simplicidad visual, paleta neutra y una experiencia de compra limpia para destacar.
        </p>
        <a href="#top" class="btn velluto-btn-outline mt-3">Volver arriba</a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
