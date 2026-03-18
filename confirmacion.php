<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Confirmación de compra';

$orderData = $_SESSION['last_order'] ?? null;

if ($orderData === null) {
    setFlashMessage('danger', 'No hay un pedido reciente para mostrar.');
    redirect('index.php');
}

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
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="info-box text-center">
                    <h1 class="section-title mb-3">Gracias por tu compra</h1>
                    <p class="section-text mx-auto mb-4">
                        Tu pedido fue registrado correctamente en Velluto. Conserva esta información como referencia de tu compra.
                    </p>

                    <div class="table-responsive">
                        <table class="table align-middle velluto-table">
                            <tbody>
                                <tr>
                                    <th scope="row">Número de pedido</th>
                                    <td><?= escape($orderData['numero_pedido']) ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Cliente</th>
                                    <td><?= escape($orderData['nombre_cliente']) ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Correo</th>
                                    <td><?= escape($orderData['correo_cliente']) ?></td>
                                </tr>

                                <tr>
                                    <th scope="row">Método de pago</th>
                                    <td><?= escape(getPaymentMethodLabel((string) $orderData['metodo_pago'])) ?></td>
                                </tr>
                                
                                <tr>
                                    <th scope="row">Total</th>
                                    <td><?= formatPrice((float) $orderData['total']) ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
                        <a href="catalogo.php" class="btn velluto-btn">Seguir comprando</a>
                        <a href="index.php" class="btn velluto-btn-outline">Volver al inicio</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
unset($_SESSION['last_order']);
require_once __DIR__ . '/includes/footer.php';
?>
