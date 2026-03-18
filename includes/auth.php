<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

function registerUser(string $nombreCompleto, string $correo, string $password, ?string $telefono = null): array
{
    global $pdo;

    $nombreCompleto = trim($nombreCompleto);
    $correo = strtolower(trim($correo));
    $telefono = $telefono !== null ? trim($telefono) : null;

    if ($nombreCompleto === '' || $correo === '' || $password === '') {
        return ['success' => false, 'message' => 'Todos los campos obligatorios deben completarse.'];
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'El correo electrónico no es válido.'];
    }

    if (strlen($password) < 8) {
        return ['success' => false, 'message' => 'La contraseña debe tener al menos 8 caracteres.'];
    }

    $checkStmt = $pdo->prepare('SELECT id FROM usuarios WHERE correo = :correo LIMIT 1');
    $checkStmt->execute(['correo' => $correo]);

    if ($checkStmt->fetch()) {
        return ['success' => false, 'message' => 'Ya existe una cuenta registrada con ese correo.'];
    }

    $passwordHash = password_hash($password, PASSWORD_ARGON2ID);

    $stmt = $pdo->prepare(
        'INSERT INTO usuarios (nombre_completo, correo, password_hash, telefono)
         VALUES (:nombre_completo, :correo, :password_hash, :telefono)'
    );

    $stmt->execute([
        'nombre_completo' => $nombreCompleto,
        'correo' => $correo,
        'password_hash' => $passwordHash,
        'telefono' => $telefono !== '' ? $telefono : null,
    ]);

    return ['success' => true, 'message' => 'Tu cuenta fue creada correctamente.'];
}

function authenticateUser(string $correo, string $password): array
{
    global $pdo;

    $correo = strtolower(trim($correo));

    if ($correo === '' || $password === '') {
        return ['success' => false, 'message' => 'Debes ingresar tu correo y contraseña.'];
    }

    $stmt = $pdo->prepare(
        'SELECT id, nombre_completo, correo, password_hash, telefono
         FROM usuarios
         WHERE correo = :correo
         LIMIT 1'
    );
    $stmt->execute(['correo' => $correo]);

    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        return ['success' => false, 'message' => 'Las credenciales son incorrectas.'];
    }

    unset($user['password_hash']);

    session_regenerate_id(true);
    $_SESSION['user'] = $user;

    return ['success' => true, 'message' => 'Inicio de sesión exitoso.'];
}

function logoutUser(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}

function requireGuest(): void
{
    if (isUserLoggedIn()) {
        redirect('index.php');
    }
}

function requireAuth(): void
{
    if (!isUserLoggedIn()) {
        setFlashMessage('danger', 'Debes iniciar sesión para acceder a esa sección.');
        redirect('login.php');
    }
}
