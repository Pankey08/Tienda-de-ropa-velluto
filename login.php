<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';

requireGuest();

$pageTitle = 'Iniciar sesión';
$errors = [];

if (isPostRequest()) {
    $correo = trim((string) ($_POST['correo'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    $result = authenticateUser($correo, $password);

    if ($result['success']) {
        setFlashMessage('success', $result['message']);
        redirect('index.php');
    }

    $errors[] = $result['message'];
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
            <div class="col-lg-6">
                <div class="auth-box">
                    <h1 class="section-title h2 mb-3">Iniciar sesión</h1>
                    <p class="section-text mb-4">
                        Accede a tu cuenta para agilizar tu compra y conservar tus datos de cliente.
                    </p>

                    <?php if ($errors): ?>
                        <div class="alert-custom-danger mb-4">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= escape($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="login.php" novalidate>
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo electrónico</label>
                            <input
                                type="email"
                                id="correo"
                                name="correo"
                                class="form-control"
                                value="<?= escape(oldInput('correo')) ?>"
                                required
                            >
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Contraseña</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control"
                                required
                            >
                        </div>

                        <div class="d-grid gap-3">
                            <button type="submit" class="btn velluto-btn">Entrar</button>
                            <a href="checkout.php" class="btn velluto-btn-outline">Continuar como invitado</a>
                        </div>
                    </form>

                    <hr class="my-4">

                    <p class="mb-0">
                        ¿Aún no tienes cuenta?
                        <a href="registro.php" class="fw-semibold">Crear una cuenta</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
