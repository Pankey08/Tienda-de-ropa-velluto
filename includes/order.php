<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

function createOrder(array $customerData): array
{
    global $pdo;

    initializeCart();
    $cart = getCart();

    if (empty($cart)) {
        return ['success' => false, 'message' => 'No puedes finalizar la compra con el carrito vacío.'];
    }

    $requiredFields = [
        'nombre_cliente' => 'nombre completo',
        'correo_cliente' => 'correo electrónico',
        'telefono_cliente' => 'teléfono',
        'direccion' => 'dirección',
        'ciudad' => 'ciudad',
        'estado' => 'estado',
        'codigo_postal' => 'código postal',
        'metodo_pago' => 'método de pago',
    ];

    $errors = validateRequiredFields($customerData, $requiredFields);

    if (!isValidEmail((string) ($customerData['correo_cliente'] ?? ''))) {
        $errors[] = 'El correo electrónico no es válido.';
    }

    $metodoPago = normalizePaymentMethod($customerData['metodo_pago'] ?? null);
    if ($metodoPago === null) {
        $errors[] = 'El método de pago seleccionado no es válido.';
    }

    if ($errors) {
        return ['success' => false, 'message' => implode(' ', $errors)];
    }

    try {
        $pdo->beginTransaction();

        foreach ($cart as $item) {
            $stmt = $pdo->prepare(
                'SELECT id, nombre, precio, stock, activo
                 FROM productos
                 WHERE id = :id
                 LIMIT 1'
            );
            $stmt->execute(['id' => (int) $item['id']]);
            $product = $stmt->fetch();

            if (!$product || (int) $product['activo'] !== 1) {
                $pdo->rollBack();
                return ['success' => false, 'message' => 'Uno de los productos ya no está disponible.'];
            }

            if ((int) $item['cantidad'] > (int) $product['stock']) {
                $pdo->rollBack();
                return ['success' => false, 'message' => 'La cantidad solicitada de uno o más productos supera el stock disponible.'];
            }
        }

        $user = getAuthenticatedUser();
        $usuarioId = $user['id'] ?? null;
        $total = calculateCartTotal();
        $numeroPedido = generateOrderNumber();

        $pedidoStmt = $pdo->prepare(
            'INSERT INTO pedidos (
                numero_pedido,
                usuario_id,
                nombre_cliente,
                correo_cliente,
                telefono_cliente,
                direccion,
                ciudad,
                estado,
                codigo_postal,
                metodo_pago,
                total,
                estado_pedido
            ) VALUES (
                :numero_pedido,
                :usuario_id,
                :nombre_cliente,
                :correo_cliente,
                :telefono_cliente,
                :direccion,
                :ciudad,
                :estado,
                :codigo_postal,
                :metodo_pago,
                :total,
                :estado_pedido
            )'
        );

        $pedidoStmt->execute([
            'numero_pedido' => $numeroPedido,
            'usuario_id' => $usuarioId,
            'nombre_cliente' => trim((string) $customerData['nombre_cliente']),
            'correo_cliente' => strtolower(trim((string) $customerData['correo_cliente'])),
            'telefono_cliente' => trim((string) $customerData['telefono_cliente']),
            'direccion' => trim((string) $customerData['direccion']),
            'ciudad' => trim((string) $customerData['ciudad']),
            'estado' => trim((string) $customerData['estado']),
            'codigo_postal' => trim((string) $customerData['codigo_postal']),
            'metodo_pago' => $metodoPago,
            'total' => $total,
            'estado_pedido' => 'pendiente',
        ]);

        $pedidoId = (int) $pdo->lastInsertId();

        $detalleStmt = $pdo->prepare(
            'INSERT INTO detalle_pedido (
                pedido_id,
                producto_id,
                cantidad,
                precio_unitario,
                subtotal
            ) VALUES (
                :pedido_id,
                :producto_id,
                :cantidad,
                :precio_unitario,
                :subtotal
            )'
        );

        $updateStockStmt = $pdo->prepare(
            'UPDATE productos
            SET stock = stock - :cantidad_restar
            WHERE id = :producto_id AND stock >= :cantidad_validar'
        );

            foreach ($cart as $item) {
                $cantidad = (int) $item['cantidad'];
                $precio = (float) $item['precio'];
                $subtotal = $cantidad * $precio;

            $detalleStmt->execute([
                'pedido_id' => $pedidoId,
                'producto_id' => (int) $item['id'],
                'cantidad' => $cantidad,
                'precio_unitario' => $precio,
                'subtotal' => $subtotal,
            ]);

            $updateStockStmt->execute([
                'cantidad_restar' => $cantidad,
                'cantidad_validar' => $cantidad,
                'producto_id' => (int) $item['id'],
            ]);

            if ($updateStockStmt->rowCount() === 0) {
                $pdo->rollBack();
                return ['success' => false, 'message' => 'No fue posible actualizar el stock de uno o más productos.'];
            }
        }   

        $pdo->commit();

        $_SESSION['last_order'] = [
            'pedido_id' => $pedidoId,
            'numero_pedido' => $numeroPedido,
            'nombre_cliente' => trim((string) $customerData['nombre_cliente']),
            'correo_cliente' => strtolower(trim((string) $customerData['correo_cliente'])),
            'metodo_pago' => $metodoPago,
            'total' => $total,
        ];

        clearCart();

        return [
            'success' => true,
            'message' => 'Pedido creado correctamente.',
            'pedido_id' => $pedidoId,
            'numero_pedido' => $numeroPedido,
        ];
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        return ['success' => false, 'message' => 'Ocurrió un error al procesar tu pedido.'];
    }

#} catch (Throwable $e) {
   # if ($pdo->inTransaction()) {
  #      $pdo->rollBack();
 #   }
#
 #   return ['success' => false, 'message' => 'Error real: ' . $e->getMessage()];
#}

}