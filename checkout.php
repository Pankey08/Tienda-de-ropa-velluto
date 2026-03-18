<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/order.php';

$pageTitle = 'Checkout';

$cartItems = getCart();
$cartTotal = calculateCartTotal();

if (empty($cartItems)) {
    setFlashMessage('danger', 'Tu carrito está vacío. Agrega productos antes de finalizar tu compra.');
    redirect('carrito.php');
}

$user = getAuthenticatedUser();
$errors = [];

$defaultData = [
    'nombre_cliente' => $user['nombre_completo'] ?? '',
    'correo_cliente' => $user['correo'] ?? '',
    'telefono_cliente' => $user['telefono'] ?? '',
    'direccion' => '',
    'ciudad' => '',
    'estado' => '',
    'codigo_postal' => '',
    'metodo_pago' => 'tarjeta',
];

if (isPostRequest()) {
    $formData = [
        'nombre_cliente' => trim((string) ($_POST['nombre_cliente'] ?? '')),
        'correo_cliente' => trim((string) ($_POST['correo_cliente'] ?? '')),
        'telefono_cliente' => trim((string) ($_POST['telefono_cliente'] ?? '')),
        'direccion' => trim((string) ($_POST['direccion'] ?? '')),
        'ciudad' => trim((string) ($_POST['ciudad'] ?? '')),
        'estado' => trim((string) ($_POST['estado'] ?? '')),
        'codigo_postal' => trim((string) ($_POST['codigo_postal'] ?? '')),
        'metodo_pago' => trim((string) ($_POST['metodo_pago'] ?? 'tarjeta')),
    ];

    $result = createOrder($formData);

    if ($result['success']) {
        setFlashMessage('success', 'Tu compra fue procesada correctamente.');
        redirect('confirmacion.php');
    }

    $errors[] = $result['message'];
    $defaultData = $formData;
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<section class="py-5">
    <div class="container">
        <div class="mb-4">
            <h1 class="section-title">Finalizar compra</h1>
            <p class="section-text">
                Completa tus datos de envío para registrar el pedido. Puedes comprar como invitado o continuar con tu cuenta si ya iniciaste sesión.
            </p>
        </div>

        <?php if ($errors): ?>
            <div class="alert-custom-danger mb-4">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= escape($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="checkout-box">
                    <h2 class="h4 mb-4">Datos del cliente y envío</h2>

                    <form method="POST" action="checkout.php" novalidate>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="nombre_cliente" class="form-label">Nombre completo</label>
                                <input
                                    type="text"
                                    id="nombre_cliente"
                                    name="nombre_cliente"
                                    class="form-control"
                                    value="<?= escape($defaultData['nombre_cliente']) ?>"
                                    required
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="correo_cliente" class="form-label">Correo electrónico</label>
                                <input
                                    type="email"
                                    id="correo_cliente"
                                    name="correo_cliente"
                                    class="form-control"
                                    value="<?= escape($defaultData['correo_cliente']) ?>"
                                    required
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="telefono_cliente" class="form-label">Teléfono</label>
                                <input
                                    type="text"
                                    id="telefono_cliente"
                                    name="telefono_cliente"
                                    class="form-control"
                                    value="<?= escape($defaultData['telefono_cliente']) ?>"
                                    required
                                >
                            </div>

                            <div class="col-md-12">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input
                                    type="text"
                                    id="direccion"
                                    name="direccion"
                                    class="form-control"
                                    value="<?= escape($defaultData['direccion']) ?>"
                                    required
                                >
                            </div>

                            <div class="col-md-4">
                                <label for="ciudad" class="form-label">Ciudad</label>
                                <input
                                    type="text"
                                    id="ciudad"
                                    name="ciudad"
                                    class="form-control"
                                    value="<?= escape($defaultData['ciudad']) ?>"
                                    required
                                >
                            </div>

                            <div class="col-md-4">
                                <label for="estado" class="form-label">Estado</label>
                                <input
                                    type="text"
                                    id="estado"
                                    name="estado"
                                    class="form-control"
                                    value="<?= escape($defaultData['estado']) ?>"
                                    required
                                >
                            </div>

                            <div class="col-md-4">
                                <label for="codigo_postal" class="form-label">Código postal</label>
                                <input
                                    type="text"
                                    id="codigo_postal"
                                    name="codigo_postal"
                                    class="form-control"
                                    value="<?= escape($defaultData['codigo_postal']) ?>"
                                    required
                                >
                            </div>

                            <div class="col-md-12">
                                <label for="metodo_pago" class="form-label">Método de pago</label>
                                <select
                                    id="metodo_pago"
                                    name="metodo_pago"
                                    class="form-select"
                                    required
                                >
                                    <option value="tarjeta" <?= $defaultData['metodo_pago'] === 'tarjeta' ? 'selected' : '' ?>>
                                        Tarjeta de crédito o débito
                                    </option>
                                    <option value="paypal" <?= $defaultData['metodo_pago'] === 'paypal' ? 'selected' : '' ?>>
                                        PayPal
                                    </option>
                                    <option value="transferencia" <?= $defaultData['metodo_pago'] === 'transferencia' ? 'selected' : '' ?>>
                                        Transferencia bancaria
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn velluto-btn">Confirmar pedido</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="checkout-box">
                    <h2 class="h4 mb-4">Resumen del pedido</h2>

                    <div class="table-responsive">
                        <table class="table align-middle velluto-table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cant.</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cartItems as $item): ?>
                                    <tr>
                                        <td><?= escape($item['nombre']) ?></td>
                                        <td><?= (int) $item['cantidad'] ?></td>
                                        <td><?= formatPrice((float) $item['precio'] * (int) $item['cantidad']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Método de pago</span>
                        <strong><?= escape(getPaymentMethodLabel((string) $defaultData['metodo_pago'])) ?></strong>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span>Total a pagar</span>
                        <strong><?= formatPrice($cartTotal) ?></strong>
                    </div>

                    <div class="d-grid gap-3">
                        <a href="carrito.php" class="btn velluto-btn-outline">Volver al carrito</a>

                        <?php if (!isUserLoggedIn()): ?>
                            <a href="login.php" class="btn velluto-btn-outline">Iniciar sesión</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>