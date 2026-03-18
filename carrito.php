<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/cart.php';

$pageTitle = 'Carrito';

if (isPostRequest()) {
    $action = (string) ($_POST['action'] ?? '');
    $productId = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;

    if ($action === 'update') {
        $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;
        $result = updateCartItem($productId, $quantity);
        setFlashMessage($result['success'] ? 'success' : 'danger', $result['message']);
        redirect('carrito.php');
    }

    if ($action === 'remove') {
        removeCartItem($productId);
        setFlashMessage('success', 'Producto eliminado del carrito.');
        redirect('carrito.php');
    }

    if ($action === 'clear') {
        clearCart();
        setFlashMessage('success', 'El carrito fue vaciado.');
        redirect('carrito.php');
    }
}

$cartItems = getCart();
$cartTotal = calculateCartTotal();

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
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h1 class="section-title mb-1">Tu carrito</h1>
                <p class="section-text mb-0">
                    Revisa tus productos antes de continuar al proceso de compra.
                </p>
            </div>

            <?php if ($cartItems): ?>
                <form method="POST" action="carrito.php">
                    <input type="hidden" name="action" value="clear">
                    <button type="submit" class="btn velluto-btn-outline">Vaciar carrito</button>
                </form>
            <?php endif; ?>
        </div>

        <?php if (!$cartItems): ?>
            <div class="empty-state">
                <h2 class="h4 mb-3">Tu carrito está vacío</h2>
                <p class="mb-4">Aún no has agregado productos. Explora nuestro catálogo para comenzar.</p>
                <a href="catalogo.php" class="btn velluto-btn">Ir al catálogo</a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="cart-box">
                        <div class="table-responsive">
                            <table class="table align-middle velluto-table">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Categoría</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Subtotal</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cartItems as $item): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <img
                                                        src="<?= escape($item['imagen']) ?>"
                                                        alt="<?= escape($item['nombre']) ?>"
                                                        style="width: 70px; height: 70px; object-fit: cover; border-radius: 12px;"
                                                    >
                                                    <div>
                                                        <strong><?= escape($item['nombre']) ?></strong>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-capitalize"><?= escape($item['categoria']) ?></td>
                                            <td><?= formatPrice((float) $item['precio']) ?></td>
                                            <td style="min-width: 150px;">
                                                <form method="POST" action="carrito.php" class="d-flex gap-2">
                                                    <input type="hidden" name="action" value="update">
                                                    <input type="hidden" name="product_id" value="<?= (int) $item['id'] ?>">
                                                    <input
                                                        type="number"
                                                        name="quantity"
                                                        min="1"
                                                        max="<?= (int) $item['stock'] ?>"
                                                        value="<?= (int) $item['cantidad'] ?>"
                                                        class="form-control js-quantity-input"
                                                        required
                                                    >
                                                    <button type="submit" class="btn velluto-btn-outline btn-sm">Actualizar</button>
                                                </form>
                                            </td>
                                            <td><?= formatPrice((float) $item['precio'] * (int) $item['cantidad']) ?></td>
                                            <td>
                                                <form method="POST" action="carrito.php">
                                                    <input type="hidden" name="action" value="remove">
                                                    <input type="hidden" name="product_id" value="<?= (int) $item['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="cart-box">
                        <h2 class="h4 mb-3">Resumen de compra</h2>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Productos</span>
                            <span><?= (int) getCartItemCount() ?></span>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span>Total</span>
                            <strong><?= formatPrice($cartTotal) ?></strong>
                        </div>

                        <div class="d-grid gap-3">
                            <a href="checkout.php" class="btn velluto-btn">Continuar al checkout</a>
                            <a href="catalogo.php" class="btn velluto-btn-outline">Seguir comprando</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
