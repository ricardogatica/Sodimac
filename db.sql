# ************************************************************
# Sequel Pro SQL dump
# Version 3408
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.1.44)
# Database: freelance_sodimac
# Generation Time: 2014-12-16 14:28:45 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table cases
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cases`;

CREATE TABLE `cases` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `cases` WRITE;
/*!40000 ALTER TABLE `cases` DISABLE KEYS */;

INSERT INTO `cases` (`id`, `name`)
VALUES
	(1,'Notas de crédito'),
	(2,'Factura por caja'),
	(3,'Tarjeta convenio'),
	(4,'Factura por canje'),
	(5,'Factura auto guía por caja'),
	(6,'Guía proveedor contra entrega'),
	(7,'Venta directa anticipada'),
	(8,'Venta anticipada stock de bodega'),
	(9,'Factura Automática contra entraba stock de bodega');

/*!40000 ALTER TABLE `cases` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table companies
# ------------------------------------------------------------

DROP TABLE IF EXISTS `companies`;

CREATE TABLE `companies` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table docs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `docs`;

CREATE TABLE `docs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `import_id` int(11) NOT NULL,
  `store_id` smallint(5) NOT NULL,
  `type_id` smallint(5) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `matched` tinyint(1) NOT NULL DEFAULT '0',
  `exported` tinyint(1) NOT NULL DEFAULT '0',
  `printable` tinyint(1) NOT NULL DEFAULT '0',
  `sendable` tinyint(1) NOT NULL DEFAULT '0',
  `dte` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `processed` datetime NOT NULL,
  `attached` datetime NOT NULL,
  `content` text NOT NULL,
  `serialize` text NOT NULL,
  `number` varchar(100) NOT NULL DEFAULT '',
  `company` varchar(255) NOT NULL DEFAULT '',
  `document` varchar(20) NOT NULL DEFAULT '',
  `payment` varchar(100) NOT NULL DEFAULT '',
  `noc` varchar(100) NOT NULL DEFAULT '',
  `noc_0` varchar(100) NOT NULL DEFAULT '',
  `ngd` varchar(100) NOT NULL DEFAULT '',
  `ngd_0` varchar(100) NOT NULL DEFAULT '',
  `ngd_1` varchar(100) NOT NULL DEFAULT '',
  `npvt` varchar(100) NOT NULL DEFAULT '',
  `npvt_0` varchar(100) NOT NULL DEFAULT '',
  `npvt_1` varchar(100) NOT NULL DEFAULT '',
  `npvt_2` varchar(100) NOT NULL DEFAULT '',
  `lote` varchar(100) NOT NULL DEFAULT '',
  `file_xml` varchar(255) NOT NULL DEFAULT '',
  `file_pdf` varchar(255) NOT NULL DEFAULT '',
  `images` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table docs_types
# ------------------------------------------------------------

DROP TABLE IF EXISTS `docs_types`;

CREATE TABLE `docs_types` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '',
  `alias` varchar(5) NOT NULL DEFAULT '',
  `regex` tinytext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `docs_types` WRITE;
/*!40000 ALTER TABLE `docs_types` DISABLE KEYS */;

INSERT INTO `docs_types` (`id`, `name`, `alias`, `regex`)
VALUES
	(1,'Factura','FAC','FACTURA'),
	(2,'Nota de credito','NC','0'),
	(3,'Orden de compra','OC','0'),
	(4,'Guia despacho','GD','0'),
	(5,'Orden de Trabajo','OT','0'),
	(6,'Otro','OTRO','0');

/*!40000 ALTER TABLE `docs_types` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table exports
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exports`;

CREATE TABLE `exports` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sending_name` varchar(100) NOT NULL DEFAULT '',
  `sending_email` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Documentos impresos o enviados';



# Dump of table stores
# ------------------------------------------------------------

DROP TABLE IF EXISTS `stores`;

CREATE TABLE `stores` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `cod` varchar(10) NOT NULL DEFAULT '',
  `mismatch_days` smallint(2) NOT NULL DEFAULT '0',
  `warning_days` smallint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `stores` WRITE;
/*!40000 ALTER TABLE `stores` DISABLE KEYS */;

INSERT INTO `stores` (`id`, `name`, `cod`, `mismatch_days`, `warning_days`)
VALUES
	(1,'HC Los Carreras','2',3,5),
	(2,'CO ViÃ±a del Mar','3',3,5),
	(3,'HC La Calera','4',3,5),
	(4,'HC Antofagasta','5',3,5),
	(5,'USE Antofagasta','6',3,5),
	(6,'HC Osorno','7',3,5),
	(7,'HC Los Angeles','9',3,5),
	(8,'HC Iquique','11',3,5),
	(9,'HC San Miguel','12',3,5),
	(10,'CO Chiloe','13',3,5),
	(11,'HC San Felipe','16',3,5),
	(12,'HC Valdivia','19',3,5),
	(13,'HC ViÃ±a del Mar','20',3,5),
	(14,'CO Cantagallo','21',3,5),
	(15,'USE Concepcion','22',3,5),
	(16,'HC San Bernardo','23',3,5),
	(17,'HC Punta Arenas','24',3,5),
	(18,'CO VicuÃ±a Mackenna','25',3,5),
	(19,'CO Rancagua','26',3,5),
	(20,'HC Calama','27',3,5),
	(21,'CO Valparaiso','29',3,5),
	(22,'CO Talcahuano','30',3,5),
	(23,'HC Arica','32',3,5),
	(24,'HC Ã‘uÃ±oa La Reina','33',3,5),
	(25,'HC Las Condes','34',3,5),
	(26,'HC Curico','35',3,5),
	(27,'HC Coyhaique','37',3,5),
	(28,'HC La Serena','39',3,5),
	(29,'CO Antofagasta','40',3,5),
	(30,'HC Puerto Montt','41',3,5),
	(31,'HC Talca','43',3,5),
	(32,'HC Temuco Cautin','44',3,5),
	(33,'Homy Parque Arauco','45',3,5),
	(34,'CO MaipÃº','48',3,5),
	(35,'HC Chillan','51',3,5),
	(36,'VTA Empresas','52',3,5),
	(37,'HC Nueva La Florida','54',3,5),
	(38,'HC Linares','55',3,5),
	(39,'HC Copiapo','57',3,5),
	(40,'HC Angol','58',3,5),
	(41,'HC Villarica','59',3,5),
	(42,'CO Vallenar','60',3,5),
	(43,'HC Huechuraba','63',3,5),
	(44,'CO La Florida','65',3,5),
	(45,'HC Ã‘uble','66',3,5),
	(46,'HC La Reina','67',3,5),
	(47,'Cauquenes','68',3,5),
	(48,'HC Paseo Estacion','70',3,5),
	(49,'USE ViÃ±a del Mar','73',3,5),
	(50,'HC El Bosque','74',3,5),
	(51,'HC Puente Alto','75',3,5),
	(52,'HC Parque Arauco','79',3,5),
	(53,'HC Mall Plaza Concepcion','81',3,5),
	(54,'HC Rancagua','83',3,5),
	(55,'El Abra','85',3,5),
	(56,'CO Huechuraba','87',3,5),
	(57,'HC MaipÃº','88',3,5),
	(58,'HC Quilpue','90',3,5),
	(59,'HC El Trebol','93',3,5),
	(60,'HC Plaza Vespucio','95',3,5),
	(61,'HC Cerrillos','96',3,5),
	(62,'HC ReÃ±aca','97',3,5),
	(63,'HC Coquimbo','98',3,5),
	(64,'Operaciones de Invetario','416',3,5),
	(65,'Base Naval Talcahuano','711',3,5),
	(66,'Carpa San Antonio','713',3,5),
	(67,'Carpa Melipilla','714',3,5),
	(68,'HC Santa Cruz','716',3,5),
	(69,'Minera Michilla','720',3,5),
	(70,'HC Quilicura','723',3,5),
	(71,'HC BioBio','724',3,5),
	(72,'HC Quinta Vergara','725',3,5),
	(73,'HC Tobalaba','726',3,5),
	(74,'HC Talca Colin','727',3,5),
	(75,'HC Alto Hospicio','728',3,5),
	(76,'HC Ovalle','729',3,5),
	(77,'San Fernando','731',3,5),
	(78,'Homy Plaza Vespucio','732',3,5),
	(79,'Homy Plaza Oeste','733',3,5),
	(80,'Homy Plaza EgaÃ±a','735',3,5),
	(81,'CANJE','0',3,5);

/*!40000 ALTER TABLE `stores` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table stores_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `stores_users`;

CREATE TABLE `stores_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` smallint(5) NOT NULL,
  `user_id` smallint(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `stores_users` WRITE;
/*!40000 ALTER TABLE `stores_users` DISABLE KEYS */;

INSERT INTO `stores_users` (`id`, `store_id`, `user_id`)
VALUES
	(8,1,4),
	(9,1,1),
	(10,5,1),
	(11,9,1),
	(12,14,1),
	(13,1,3),
	(14,2,3),
	(15,3,3),
	(16,4,3),
	(17,5,3),
	(18,6,3),
	(19,7,3),
	(20,8,3),
	(21,9,3),
	(22,10,3),
	(23,11,3),
	(24,12,3),
	(25,13,3),
	(26,14,3),
	(27,15,3),
	(28,16,3),
	(29,17,3),
	(30,18,3),
	(31,19,3),
	(32,20,3),
	(33,21,3),
	(34,22,3),
	(35,23,3),
	(36,24,3),
	(37,25,3),
	(38,26,3),
	(39,27,3),
	(40,28,3),
	(41,29,3),
	(42,30,3),
	(43,31,3),
	(44,32,3),
	(45,33,3),
	(46,34,3),
	(47,35,3),
	(48,36,3),
	(49,37,3),
	(50,38,3),
	(51,39,3),
	(52,40,3),
	(53,41,3),
	(54,42,3),
	(55,43,3),
	(56,44,3),
	(57,45,3),
	(58,46,3),
	(59,47,3),
	(60,48,3),
	(61,49,3),
	(62,50,3),
	(63,51,3),
	(64,52,3),
	(65,53,3),
	(66,54,3),
	(67,55,3),
	(68,56,3),
	(69,57,3),
	(70,58,3),
	(71,59,3),
	(72,60,3),
	(73,61,3),
	(74,62,3),
	(75,63,3),
	(76,64,3),
	(77,65,3),
	(78,66,3),
	(79,67,3),
	(80,68,3),
	(81,69,3),
	(82,70,3),
	(83,71,3),
	(84,72,3),
	(85,73,3),
	(86,74,3),
	(87,75,3),
	(88,76,3),
	(89,77,3),
	(90,78,3),
	(91,79,3),
	(92,80,3);

/*!40000 ALTER TABLE `stores_users` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `profile` varchar(10) NOT NULL DEFAULT '',
  `username` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `profile`, `username`, `password`, `name`)
VALUES
	(1,'developer','developer','da5d2d88ea4942d4a94e0d5d41ffba22091191d5','Developer'),
	(3,'admin','admin','da5d2d88ea4942d4a94e0d5d41ffba22091191d5','Administrador'),
	(4,'user','user','da5d2d88ea4942d4a94e0d5d41ffba22091191d5','Usuario');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
