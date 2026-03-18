# Velluto
<img width="1369" height="1087" alt="image" src="https://github.com/user-attachments/assets/a552c641-3c72-4229-8d74-b60062e49c03" />

## Alumno: Yaotzin Israel Pineda Pineda

Velluto es una tienda de ropa para hombre y mujer, desarrollada con PHP, MySQL, Bootstrap, HTML, CSS y JavaScript.

## Características

- Catálogo de productos cargado desde MySQL
- Filtro por categoría
- Búsqueda por nombre
- Detalle de producto
- Carrito con sesiones PHP
- Registro e inicio de sesión para clientes
- Opción de compra como invitado
- Checkout funcional
- Registro de pedidos en base de datos
- Página de confirmación de compra
- Página personalizada 404

## Tecnologías utilizadas
- PHP
- MySQL
- Apache
- Bootstrap 5
- HTML5
- CSS3
- JavaScript
## Estructura del proyecto

- `index.php` Página principal
- `catalogo.php` Catálogo general
- `producto.php` Detalle individual de producto
- `carrito.php` Carrito de compra
- `checkout.php` Finalización de compra
- `login.php` Inicio de sesión
- `registro.php` Registro de clientes
- `confirmacion.php` Confirmación de pedido
- `logout.php` Cierre de sesión
- `404.php` Página no encontrada

## Requisitos

- Apache
- PHP 8 o superior
- MySQL 8 o MariaDB compatible
- Extensión PDO MySQL habilitada

## Instalación

1. Copiar la carpeta del proyecto en `/var/www/html/`
2. Iniciar Apache y MySQL
3. Crear la base de datos ejecutando el archivo `sql/velluto.sql`
4. Configurar credenciales en `config/database.php`
5. Abrir en navegador `http://localhost/velluto/`

## Base de datos

El archivo `sql/velluto.sql` contiene:

- creación de base de datos
- creación de tablas
- inserción de productos de prueba

## Usuario

El registro se realiza desde la página `registro.php`.

## Notas
- El sistema de pago real no está implementado
- La seguridad de contraseñas con Argon2i
