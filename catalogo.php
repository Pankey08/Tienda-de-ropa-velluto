<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/cart.php';

$pageTitle = 'Catálogo';

if (isPostRequest()) {
    $productId = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;

    $result = addProductToCart($productId, $quantity);
    setFlashMessage($result['success'] ? 'success' : 'danger', $result['message']);
    redirect('catalogo.php');
}

$categoria = normalizeCategory($_GET['categoria'] ?? null);
$search = trim((string) ($_GET['q'] ?? ''));

$sql = 'SELECT id, nombre, categoria, precio, talla, color, material, descripcion, stock, imagen
        FROM productos
        WHERE activo = 1';

$params = [];

if ($categoria !== null) {
    $sql .= ' AND categoria = :categoria';
    $params['categoria'] = $categoria;
}

if ($search !== '') {
    $sql .= ' AND nombre LIKE :search';
    $params['search'] = '%' . $search . '%';
}

$sql .= ' ORDER BY id DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$placeholders = $stmt->fetchAll();

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

<section class="py-5">
    <div class="container">
        <div class="mb-4">
            <h1 class="section-title">Catálogo de playeras</h1>
            <p class="section-text">
                Explora la colección completa de Velluto. Puedes ver todos los productos, buscar por nombre o filtrar por categoría.
            </p>
        </div>

        <div class="filter-box mb-4">
            <form method="GET" action="catalogo.php" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="q" class="form-label">Buscar producto</label>
                    <input
                        type="text"
                        id="q"
                        name="q"
                        class="form-control"
                        placeholder="Ejemplo: Essential Ivory"
                        value="<?= escape($search) ?>"
                    >
                </div>

                <div class="col-md-4">
                    <label for="categoria" class="form-label">Filtrar por categoría</label>
                    <select name="categoria" id="categoria" class="form-select">
                        <option value="">Todas</option>
                        <option value="hombre" <?= $categoria === 'hombre' ? 'selected' : '' ?>>Hombre</option>
                        <option value="mujer" <?= $categoria === 'mujer' ? 'selected' : '' ?>>Mujer</option>
                    </select>
                </div>

                <div class="col-md-3 d-grid">
                    <button type="submit" class="btn velluto-btn">Aplicar filtros</button>
                </div>
            </form>
        </div>

        <?php if (!$placeholders): ?>
            <div class="empty-state">
                <h2 class="h4 mb-3">No se encontraron productos</h2>
                <p class="mb-0">Prueba con otra búsqueda o elimina los filtros aplicados.</p>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($placeholders as $product): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="velluto-card h-100">
                            <img
                                src="<?= escape($product['imagen']) ?>"
                                alt="<?= escape($product['nombre']) ?>"
                                class="velluto-card-img"
                            >

                            <div class="velluto-card-body d-flex flex-column">
                                <h2 class="product-title"><?= escape($product['nombre']) ?></h2>
                                <p class="product-meta text-capitalize mb-2">Categoría: <?= escape($product['categoria']) ?></p>
                                <p class="product-price mb-2"><?= formatPrice((float) $product['precio']) ?></p>

                                <ul class="product-meta mb-3">
                                    <li>Talla: <?= escape($product['talla']) ?></li>
                                    <li>Color: <?= escape($product['color']) ?></li>
                                    <li>Material: <?= escape($product['material']) ?></li>
                                    <li>Stock: <?= (int) $product['stock'] ?></li>
                                </ul>

                                <p class="product-meta flex-grow-1">
                                    <?= escape(mb_strimwidth($product['descripcion'], 0, 110, '...')) ?>
                                </p>

                                <div class="d-grid gap-2 mt-3">
                                    <a href="producto.php?id=<?= (int) $product['id'] ?>" class="btn velluto-btn-outline">Ver detalle</a>

                                    <form method="POST" action="catalogo.php">
                                        <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn velluto-btn w-100">Agregar al carrito</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
