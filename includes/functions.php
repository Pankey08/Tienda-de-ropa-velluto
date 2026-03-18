<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    $isHttps = (
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['SERVER_PORT'] ?? null) == 443)
    );

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();
}

function redirect(string $location): void
{
    header("Location: {$location}");
    exit;
}

function escape(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function isPostRequest(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function isUserLoggedIn(): bool
{
    return isset($_SESSION['user']) && is_array($_SESSION['user']);
}

function getAuthenticatedUser(): ?array
{
    return $_SESSION['user'] ?? null;
}

function setFlashMessage(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function getFlashMessage(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

function oldInput(string $key, string $default = ''): string
{
    return isset($_POST[$key]) ? trim((string) $_POST[$key]) : $default;
}

function normalizeCategory(?string $category): ?string
{
    $allowed = ['hombre', 'mujer'];

    if ($category === null) {
        return null;
    }

    $category = strtolower(trim($category));

    return in_array($category, $allowed, true) ? $category : null;
}

function initializeCart(): void
{
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
}

function getCart(): array
{
    initializeCart();
    return $_SESSION['cart'];
}

function getCartItemCount(): int
{
    initializeCart();

    $count = 0;
    foreach ($_SESSION['cart'] as $item) {
        $count += (int) ($item['cantidad'] ?? 0);
    }

    return $count;
}

function clearCart(): void
{
    $_SESSION['cart'] = [];
}

function calculateCartTotal(): float
{
    initializeCart();

    $total = 0.0;
    foreach ($_SESSION['cart'] as $item) {
        $cantidad = (int) ($item['cantidad'] ?? 0);
        $precio = (float) ($item['precio'] ?? 0);
        $total += $cantidad * $precio;
    }

    return $total;
}

function formatPrice(float $price): string
{
    return '$' . number_format($price, 2, '.', ',');
}

function validateRequiredFields(array $data, array $requiredFields): array
{
    $errors = [];

    foreach ($requiredFields as $field => $label) {
        $value = trim((string) ($data[$field] ?? ''));

        if ($value === '') {
            $errors[] = "El campo {$label} es obligatorio.";
        }
    }

    return $errors;
}

function isValidEmail(string $email): bool
{
    return filter_var(trim($email), FILTER_VALIDATE_EMAIL) !== false;
}

function generateOrderNumber(): string
{
    return 'VEL-' . date('YmdHis') . '-' . random_int(1000, 9999);
}

function normalizePaymentMethod(?string $method): ?string
{
    $allowed = ['tarjeta', 'paypal', 'transferencia'];

    if ($method === null) {
        return null;
    }

    $method = strtolower(trim($method));

    return in_array($method, $allowed, true) ? $method : null;
}

function getPaymentMethodLabel(string $method): string
{
    return match ($method) {
        'tarjeta' => 'Tarjeta de crédito o débito',
        'paypal' => 'PayPal',
        'transferencia' => 'Transferencia bancaria',
        default => 'No especificado',
    };
}