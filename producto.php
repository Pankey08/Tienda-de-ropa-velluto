<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/cart.php';

$productId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($productId <= 0) {
    redirect('404.php');
}

if (isPostRequest()) {
    $postProductId = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;

    $result = addProductToCart($postProductId, $quantity);
    setFlashMessage($result['success'] ? 'success' : 'danger', $result['message']);
    redirect('producto.php?id=' . $postProductId);
}

$stmt = $pdo->prepare(
    'SELECT id, nombre, categoria, precio, talla, color, material, descripcion, stock, imagen
     FROM productos
     WHERE id = :id AND activo = 1
     LIMIT 1'
);
$stmt->execute(['id' => $productId]);

$product = $stmt->fetch();

if (!$product) {
    redirect('404.php');
}

$pageTitle = $product['nombre'];

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
        <div class="row g-5 align-items-start">
            <div class="col-lg-6">
                <div class="velluto-card">
                    <img
                        src="<?= escape($product['imagen']) ?>"
                        alt="<?= escape($product['nombre']) ?>"
                        class="velluto-card-img"
                        style="height: 520px;"
                    >
                </div>
            </div>

            <div class="col-lg-6">
                <div class="info-box">
                    <p class="text-uppercase small fw-semibold mb-2 text-muted">Detalle del producto</p>
                    <h1 class="section-title mb-3"><?= escape($product['nombre']) ?></h1>

                    <p class="product-price mb-4"><?= formatPrice((float) $product['precio']) ?></p>

                    <table class="table velluto-table align-middle">
                        <tbody>
                            <tr>
                                <th scope="row">Categoría</th>
                                <td class="text-capitalize"><?= escape($product['categoria']) ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Talla</th>
                                <td><?= escape($product['talla']) ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Color</th>
                                <td><?= escape($product['color']) ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Material</th>
                                <td><?= escape($product['material']) ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Stock disponible</th>
                                <td><?= (int) $product['stock'] ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-4">
                        <h2 class="h5 mb-3">Descripción</h2>
                        <p class="section-text"><?= escape($product['descripcion']) ?></p>
                    </div>

                    <form method="POST" action="producto.php?id=<?= (int) $product['id'] ?>" class="mt-4">
                        <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">

                        <div class="row g-3 align-items-end">
                            <div class="col-sm-4">
                                <label for="quantity" class="form-label">Cantidad</label>
                                <input
                                    type="number"
                                    min="1"
                                    max="<?= (int) $product['stock'] ?>"
                                    name="quantity"
                                    id="quantity"
                                    class="form-control js-quantity-input"
                                    value="1"
                                    required
                                >
                            </div>

                            <div class="col-sm-8 d-grid">
                                <button type="submit" class="btn velluto-btn">Agregar al carrito</button>
                            </div>
                        </div>
                    </form>

                    <div class="d-flex flex-wrap gap-3 mt-4">
                        <a href="catalogo.php" class="btn velluto-btn-outline">Volver al catálogo</a>
                        <a href="carrito.php" class="btn velluto-btn">Ir al carrito</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
