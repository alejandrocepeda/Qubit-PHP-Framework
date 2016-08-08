-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.6.17 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             9.3.0.5075
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for felipeb
CREATE DATABASE IF NOT EXISTS `qubitframework` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `qubitframework`;

-- Dumping structure for table felipeb.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `rut` int(15) NOT NULL,
  `dv` char(1) COLLATE latin1_spanish_ci NOT NULL,
  `nombre` varchar(30) COLLATE latin1_spanish_ci NOT NULL,
  `apellido` varchar(30) COLLATE latin1_spanish_ci NOT NULL,
  `password` varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  `administrador` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rut`),
  UNIQUE KEY `UNIQUE` (`rut`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- Dumping data for table felipeb.usuarios: ~6 rows (approximately)
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` (`rut`, `dv`, `nombre`, `apellido`, `password`, `administrador`) VALUES
	(2, 'A', 'FELIPE1', 'BECERRA', '8cb2237d0679ca88db6464eac60da96345513964', 1),
	(3, 'A', 'USUARIO1', 'APELLIDO1', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 0),
	(4, 'A', 'USUARIO2', 'APELLIDO2', 'e4615467017b8f60b6100f9ba9e77669d43f5d2e', 0),
	(9, 'B', 'USUARIO5', 'APELLIDO5', '5491c11f9ee6ff22b260040f4f1b1a3442d127c4', 0),
	(14357229, 'A', 'ALEJANDRO', 'Cepeda', '673b05208e462a39ea535c83966c649df235ab5a', 1),
	(16105703, 'p', '1411', 'felipe', '673b05208e462a39ea535c83966c649df235ab5a', 1);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;

-- Dumping structure for procedure felipeb.pr_nuevo_usuario
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `pr_nuevo_usuario`(IN `rut` INT, IN `dv` CHAR(1), IN `nombre` CHAR(30), IN `apellido` CHAR(30), IN `pass` VARCHAR(300), IN `adm` TINYINT)
IF (SELECT 1 = 1 FROM usuarios U WHERE U.rut = rut) THEN
BEGIN
    SELECT 0;
END;
ELSE
BEGIN
	INSERT INTO `maracom_felipeb`.`usuarios`
		(`rut`, `dv`, `nombre`, `apellido`,  `password`, `administrador`)
	VALUES    
		(rut, dv, nombre, apellido, pass, adm);
	SELECT rut;
END;
END IF//
DELIMITER ;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
