/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.6-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: velluto
-- ------------------------------------------------------
-- Server version	11.8.6-MariaDB-2 from Debian

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `detalle_pedido`
--

DROP TABLE IF EXISTS `detalle_pedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_pedido` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pedido_id` int(10) unsigned NOT NULL,
  `producto_id` int(10) unsigned NOT NULL,
  `cantidad` int(10) unsigned NOT NULL DEFAULT 1,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_detalle_pedido` (`pedido_id`),
  KEY `fk_detalle_producto` (`producto_id`),
  CONSTRAINT `fk_detalle_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_detalle_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_pedido`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `detalle_pedido` WRITE;
/*!40000 ALTER TABLE `detalle_pedido` DISABLE KEYS */;
INSERT INTO `detalle_pedido` VALUES
(11,11,9,1,349.00,349.00,'2026-03-17 21:01:17'),
(12,12,2,1,319.00,319.00,'2026-03-17 21:04:07'),
(13,13,9,1,349.00,349.00,'2026-03-17 21:08:29');
/*!40000 ALTER TABLE `detalle_pedido` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedidos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `numero_pedido` varchar(30) NOT NULL,
  `usuario_id` int(10) unsigned DEFAULT NULL,
  `nombre_cliente` varchar(150) NOT NULL,
  `correo_cliente` varchar(120) NOT NULL,
  `telefono_cliente` varchar(20) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `ciudad` varchar(100) NOT NULL,
  `estado` varchar(100) NOT NULL,
  `codigo_postal` varchar(10) NOT NULL,
  `metodo_pago` enum('tarjeta','paypal','transferencia') NOT NULL DEFAULT 'tarjeta',
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `estado_pedido` enum('pendiente','pagado','enviado','entregado','cancelado') NOT NULL DEFAULT 'pendiente',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_pedido` (`numero_pedido`),
  KEY `fk_pedidos_usuario` (`usuario_id`),
  CONSTRAINT `fk_pedidos_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedidos`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `pedidos` WRITE;
/*!40000 ALTER TABLE `pedidos` DISABLE KEYS */;
INSERT INTO `pedidos` VALUES
(11,'VEL-20260317210117-5776',3,'Yaotzin Israel Pineda Pineda','yaotzin@yaotzin.com','1111111111','adasd','asd','asd','asd','tarjeta',349.00,'pendiente','2026-03-17 21:01:17'),
(12,'VEL-20260317210407-1372',3,'Yaotzin Israel Pineda Pineda','yaotzin@yaotzin.com','1111111111','adasd','ad','ad','ad','paypal',319.00,'pendiente','2026-03-17 21:04:07'),
(13,'VEL-20260317210829-2367',3,'Yaotzin Israel Pineda Pineda','yaotzin@yaotzin.com','1111111111','78','78','78','8','tarjeta',349.00,'pendiente','2026-03-17 21:08:29');
/*!40000 ALTER TABLE `pedidos` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `productos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `categoria` enum('hombre','mujer') NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `talla` varchar(20) NOT NULL,
  `color` varchar(50) NOT NULL,
  `material` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `stock` int(10) unsigned NOT NULL DEFAULT 0,
  `imagen` varchar(255) NOT NULL DEFAULT 'assets/img/placeholders/producto-default.jpg',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` VALUES
(1,'Playera Essential Ivory Hombre','hombre',299.00,'M','Marfil','Algodón premium','Playera de corte recto con diseño minimalista para uso diario.',1000,'assets/img/placeholders/hombre1.jpg',1,'2026-03-17 16:41:38','2026-03-17 21:12:51'),
(2,'Playera Urban Sand Hombre','hombre',319.00,'L','Arena','Algodón peinado','Playera casual con acabado suave y estilo contemporáneo.',1000,'assets/img/placeholders/hombre2.jpg',1,'2026-03-17 16:41:38','2026-03-17 21:12:51'),
(3,'Playera Classic Beige Hombre','hombre',289.00,'S','Beige','Algodón ligero','Diseño sobrio y cómodo ideal para looks versátiles.',1000,'assets/img/placeholders/hombre3.jpg',1,'2026-03-17 16:41:38','2026-03-17 21:12:51'),
(4,'Playera Velluto Studio Hombre','hombre',349.00,'XL','Carbón','Algodón orgánico','Playera elegante de inspiración urbana con textura suave.',1000,'assets/img/placeholders/hombre4.jpg',1,'2026-03-17 16:41:38','2026-03-17 21:12:51'),
(5,'Playera Soft Line Hombre','hombre',309.00,'M','Blanco','Algodón stretch','Prenda básica premium con ajuste moderno.',1000,'assets/img/placeholders/hombre5.jpg',1,'2026-03-17 16:41:38','2026-03-17 21:12:51'),
(6,'Playera Essential Ivory Mujer','mujer',299.00,'S','Marfil','Algodón premium','Playera femenina de estilo limpio y acabado delicado.',1000,'assets/img/placeholders/mujer1.jpg',1,'2026-03-17 16:41:38','2026-03-17 21:12:51'),
(7,'Playera Urban Sand Mujer','mujer',319.00,'M','Arena','Algodón peinado','Diseño minimalista para outfits casuales y elegantes.',1000,'assets/img/placeholders/mujer2.jpg',1,'2026-03-17 16:41:38','2026-03-17 21:12:51'),
(8,'Playera Classic Beige Mujer','mujer',289.00,'L','Beige','Algodón ligero','Playera cómoda y fresca para uso diario.',1000,'assets/img/placeholders/mujer3.jpg',1,'2026-03-17 16:41:38','2026-03-17 21:12:51'),
(9,'Playera Velluto Studio Mujer','mujer',349.00,'M','Carbón','Algodón orgánico','Diseño contemporáneo con silueta refinada.',1000,'assets/img/placeholders/mujer4.jpg',1,'2026-03-17 16:41:38','2026-03-17 21:12:51'),
(10,'Playera Soft Line Mujer','mujer',309.00,'S','Blanco','Algodón stretch','Prenda básica premium con enfoque elegante y simple.',1000,'assets/img/placeholders/mujer5.jpg',1,'2026-03-17 16:41:38','2026-03-17 21:12:51');
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_completo` varchar(150) NOT NULL,
  `correo` varchar(120) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES
(1,'chino','chino@chino.com','$argon2id$v=19$m=65536,t=4,p=1$TGVQd0ZySDNTb1E0Y0tQcw$nfCcecpIqTCFnVch59jHH2J/TIyM0/mkR+OKAWNoCtE',NULL,'2026-03-17 17:44:47','2026-03-17 17:44:47'),
(2,'<h1>Chino</h1>','chino2@chino.com','$argon2id$v=19$m=65536,t=4,p=1$elVtdVEvUC4zVHdHblQzbw$H8jIv13pl012uV8o/KImD5+uHYBHIUkceR6r6apM80A',NULL,'2026-03-17 18:02:16','2026-03-17 18:02:16'),
(3,'Yaotzin Israel Pineda Pineda','yaotzin@yaotzin.com','$argon2id$v=19$m=65536,t=4,p=1$bXF3RXpqYjBMV0xrb3BUcA$a1VFe0sroJFMNUelAM2vhQI5AswBkmzRBjyRNF9uLRY','1111111111','2026-03-17 20:08:44','2026-03-17 20:08:44');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-03-18 14:58:10
