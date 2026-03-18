<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';

requireGuest();

$pageTitle = 'Registro';
$errors = [];

if (isPostRequest()) {
    $nombreCompleto = trim((string) ($_POST['nombre_completo'] ?? ''));
    $correo = trim((string) ($_POST['correo'] ?? ''));
    $telefono = trim((string) ($_POST['telefono'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $passwordConfirmation = (string) ($_POST['password_confirmation'] ?? '');

    if ($password !== $passwordConfirmation) {
        $errors[] = 'La confirmación de la contraseña no coincide.';
    }

    if (!$errors) {
        $result = registerUser($nombreCompleto, $correo, $password, $telefono);

        if ($result['success']) {
            setFlashMessage('success', 'Tu cuenta fue creada correctamente. Ahora puedes iniciar sesión.');
            redirect('login.php');
        }

        $errors[] = $result['message'];
    }
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="auth-box">
                    <h1 class="section-title h2 mb-3">Crear cuenta</h1>
                    <p class="section-text mb-4">
                        Regístrate para guardar tus datos y facilitar futuras compras en Velluto.
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

                    <form method="POST" action="registro.php" novalidate>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="nombre_completo" class="form-label">Nombre completo</label>
                                <input
                                    type="text"
                                    id="nombre_completo"
                                    name="nombre_completo"
                                    class="form-control"
                                    value="<?= escape(oldInput('nombre_completo')) ?>"
                                    required
                                >
                            </div>

                            <div class="col-md-6">
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

                            <div class="col-md-6">
                                <label for="telefono" class="form-label">Teléfono opcional</label>
                                <input
                                    type="text"
                                    id="telefono"
                                    name="telefono"
                                    class="form-control"
                                    value="<?= escape(oldInput('telefono')) ?>"
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label">Contraseña</label>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-control"
                                    required
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    class="form-control"
                                    required
                                >
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn velluto-btn">Crear cuenta</button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <p class="mb-0">
                        ¿Ya tienes una cuenta?
                        <a href="login.php" class="fw-semibold">Inicia sesión aquí</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
