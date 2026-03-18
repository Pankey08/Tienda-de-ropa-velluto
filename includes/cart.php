<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

function addProductToCart(int $productId, int $quantity = 1): array
{
    global $pdo;

    initializeCart();

    if ($productId <= 0 || $quantity <= 0) {
        return ['success' => false, 'message' => 'Datos inválidos para agregar al carrito.'];
    }

    $stmt = $pdo->prepare(
        'SELECT id, nombre, precio, stock, imagen, categoria
         FROM productos
         WHERE id = :id AND activo = 1
         LIMIT 1'
    );
    $stmt->execute(['id' => $productId]);

    $product = $stmt->fetch();

    if (!$product) {
        return ['success' => false, 'message' => 'El producto no existe o no está disponible.'];
    }

    $currentQuantity = $_SESSION['cart'][$productId]['cantidad'] ?? 0;
    $newQuantity = $currentQuantity + $quantity;

    if ($newQuantity > (int) $product['stock']) {
        return ['success' => false, 'message' => 'No hay suficiente stock disponible.'];
    }

    $_SESSION['cart'][$productId] = [
        'id' => (int) $product['id'],
        'nombre' => $product['nombre'],
        'precio' => (float) $product['precio'],
        'imagen' => $product['imagen'],
        'categoria' => $product['categoria'],
        'cantidad' => $newQuantity,
        'stock' => (int) $product['stock'],
    ];

    return ['success' => true, 'message' => 'Producto agregado al carrito.'];
}

function updateCartItem(int $productId, int $quantity): array
{
    initializeCart();

    if (!isset($_SESSION['cart'][$productId])) {
        return ['success' => false, 'message' => 'El producto no está en el carrito.'];
    }

    if ($quantity <= 0) {
        unset($_SESSION['cart'][$productId]);
        return ['success' => true, 'message' => 'Producto eliminado del carrito.'];
    }

    $stock = (int) $_SESSION['cart'][$productId]['stock'];
    if ($quantity > $stock) {
        return ['success' => false, 'message' => 'La cantidad solicitada supera el stock disponible.'];
    }

    $_SESSION['cart'][$productId]['cantidad'] = $quantity;

    return ['success' => true, 'message' => 'Cantidad actualizada correctamente.'];
}

function removeCartItem(int $productId): void
{
    initializeCart();

    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}
