-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema tp_web2
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `tp_web2` ;

-- -----------------------------------------------------
-- Schema tp_web2
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `tp_web2` DEFAULT CHARACTER SET utf8 ;
USE `tp_web2` ;

-- -----------------------------------------------------
-- Table `localidad`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `localidad` ;

CREATE TABLE IF NOT EXISTS `localidad` (
  `id_localidad` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_localidad`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user` ;

CREATE TABLE IF NOT EXISTS `user` (
  `id_user` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(45) NOT NULL,
  `username` VARCHAR(45) NOT NULL,
  `pass` VARCHAR(45) NOT NULL,
  `moderador` TINYINT NOT NULL,
  `img_path` VARCHAR(100) NULL,
  `id_localidad` INT NOT NULL,
  PRIMARY KEY (`id_user`),
  CONSTRAINT `fk_user_localidad`
    FOREIGN KEY (`id_localidad`)
    REFERENCES `localidad` (`id_localidad`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `publicacion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `publicacion` ;

CREATE TABLE IF NOT EXISTS `publicacion` (
  `id_publicacion` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NOT NULL,
  `desc` TEXT NOT NULL,
  `img_path` VARCHAR(100) NULL,
  `fecha_post` DATETIME NOT NULL,
  `fecha_evento` DATE NOT NULL,
  `inicio` TIME NOT NULL,
  `fin` TIME NOT NULL,
  `id_user` INT NOT NULL,
  `id_localidad` INT NOT NULL,
  PRIMARY KEY (`id_publicacion`),
  CONSTRAINT `fk_publicacion_user1`
    FOREIGN KEY (`id_user`)
    REFERENCES `user` (`id_user`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_publicacion_localidad1`
    FOREIGN KEY (`id_localidad`)
    REFERENCES `localidad` (`id_localidad`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `comentario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `comentario` ;

CREATE TABLE IF NOT EXISTS `comentario` (
  `id_comentario` INT NOT NULL AUTO_INCREMENT,
  `mensaje` TEXT NOT NULL,
  `fecha_post` DATETIME NOT NULL,
  `id_user` INT NOT NULL,
  `id_publicacion` INT NOT NULL,
  PRIMARY KEY (`id_comentario`),
  CONSTRAINT `fk_comentario_user1`
    FOREIGN KEY (`id_user`)
    REFERENCES `user` (`id_user`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comentario_publicacion1`
    FOREIGN KEY (`id_publicacion`)
    REFERENCES `publicacion` (`id_publicacion`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;


USE tp_web2;

INSERT INTO localidad (id_localidad, nombre) VALUES
(1, 'CABA'),
(2, 'La Plata'),
(3, 'Mar del Plata'),
(4, 'Bahía Blanca'),
(5, 'Quilmes'),
(6,'Tigre'),
(7, 'Lomas de Zamora'),
(8, 'Tandil'),
(9, 'Junín'),
(10, 'Azul'),
(11, 'Olavarría'),
(12, 'Pergamino'),
(13, 'Bahía Blanca'),
(14, 'San Nicolás'),
(15, 'Chivilcoy'),
(16, 'Luján'),
(17, 'Necochea'),
(18, 'Avellaneda'),
(19, 'Buenos Aires'),
(20, 'Córdoba'),
(21, 'Rosario'),
(22, 'Mendoza'),
(23, 'Salta'),
(24, 'Chubut'),
(25, 'Santa Fe'),
(26, 'Tucumán'),
(27, 'Jujuy'),
(28, 'San Juan'),
(29, 'Catamarca'),
(30, 'Chaco'),
(31, 'Chubut'),
(32, 'Córdoba'),
(33, 'Corrientes'),
(34, 'Entre Ríos'),
(35, 'Formosa'),
(36, 'Jujuy'),
(37, 'La Pampa'),
(38, 'La Rioja'),
(39, 'Mendoza'),
(40, 'Misiones'),
(41, 'Neuquén'),
(42, 'Río Negro'),
(43, 'Salta'),
(44, 'San Juan'),
(45, 'San Luis'),
(46, 'Santa Cruz'),
(47, 'Santa Fe'),
(48, 'Santiago del Estero'),
(49, 'Tierra del Fuego'),
(50, 'Tucumán'),
(51, 'Otro');


INSERT INTO user (id_user, email, username, pass, moderador, img_path, id_localidad) VALUES
(1, 'juanito@gmail.com', 'Juanito', 'contraseña1', 0, '../public/img/user/1.jpg', 1),
(2, 'maria.jose@hotmail.com', 'MariaJose', 'contraseña2', 0, '../public/img/user/2.jpg', 2),
(3, 'carlos.rosa@yahoo.com', 'CarlosRosa', 'contraseña3', 0, '../public/img/user/3.jpg', 3),
(4, 'laura23@gmail.com', 'Laura', 'contraseña4', 0, null, 1),
(5, 'pedro85@hotmail.com', 'Pedro', 'contraseña5', 0, null, 2),
(6, 'ana.banana@yahoo.com', 'AnaBanana', 'contraseña6', 0, null, 3),
(7, 'martin.hdez@gmail.com', 'MartinHdez', 'contraseña7', 0, null, 1),
(8, 'vicky_88@hotmail.com', 'Vicky', 'contraseña8', 0, null, 2),
(9, 'diego_perez@yahoo.com', 'DiegoPerez', 'contraseña9', 0, null, 3),
(10, 'lucia.lopez@gmail.com', 'LuciaLopez', 'contraseña10', 0, null, 1),
(11, 'test@test.com', 'EventArExp1', '123456', 0, null, 1),
(12, 'admin@adm.com', 'admin', 'admin123', 1, null, 1);

INSERT INTO publicacion (id_publicacion, title, publicacion.desc, img_path, fecha_post, fecha_evento, inicio, fin, id_user, id_localidad) VALUES
(1, 'Concierto en el Parque', '¡Ven a disfrutar de un concierto al aire libre en el Parque Centenario!', '../public/img/publicacion/1.jpg', '2023-11-28 10:00:00', '2023-12-15', '18:00:00', '21:00:00', 1, 1),
(2, 'Feria de Artesanos', 'Descubre las creaciones de talentosos artesanos locales en nuestra feria mensual.', '../public/img/publicacion/2.jpg', '2023-11-28 12:30:00', '2023-12-10', '14:00:00', '20:00:00', 2, 2),
(3, 'Charla sobre Medio Ambiente', 'Únete a nuestra charla sobre sostenibilidad y cuidado del medio ambiente.', '../public/img/publicacion/3.jpg', '2023-11-29 09:45:00', '2023-12-05', '15:30:00', '17:00:00', 3, 3),
(4, 'Noche de Tango', 'Experimenta la pasión del tango argentino en nuestra noche especial.', '../public/img/publicacion/4.jpg', '2023-11-30 15:20:00', '2023-11-08', '19:00:00', '22:00:00', 4, 4),
(5, 'Carrera Solidaria', 'Participa en nuestra carrera solidaria para apoyar a organizaciones benéficas locales.', '../public/img/publicacion/5.jpg', '2023-11-01 08:00:00', '2023-12-20', '09:30:00', '12:00:00', 5, 5),
(6, 'Exposición de Arte Moderno', 'Descubre las últimas obras de artistas modernos en nuestra exposición mensual.', NULL, '2023-11-02 11:15:00', '2023-12-18', '14:00:00', '19:00:00', 6, 6),
(7, 'Taller de Cocina Argentina', 'Aprende a cocinar platos tradicionales argentinos en nuestro taller interactivo.', NULL, '2023-11-03 14:30:00', '2023-12-12', '18:30:00', '21:00:00', 7, 7),
(8, 'Ciclo de Cine Indie', 'Disfruta de películas independientes en nuestro ciclo de cine los fines de semana.', NULL, '2023-11-04 10:45:00', '2023-12-09', '20:00:00', '23:00:00', 8, 8),
(9, 'Fiesta Latina', 'Baila al ritmo de la música latina en nuestra fiesta temática.', NULL, '2023-11-05 13:20:00', '2023-12-14', '22:00:00', '02:00:00', 9, 9),
(10, 'Charla de Innovación Tecnológica', 'Explora las últimas tendencias tecnológicas en nuestra charla informativa.', NULL, '2023-12-06 09:30:00', '2023-12-11', '16:00:00', '18:00:00', 10, 10),
(11, 'Recital de Jazz en Vivo', 'Sumérgete en la magia del jazz con nuestro recital en vivo.', NULL, '2023-11-07 15:45:00', '2023-12-17', '19:30:00', '21:30:00', 1, 11),
(12, 'Feria del Libro', 'Explora una amplia variedad de libros en nuestra feria anual del libro.', NULL, '2023-11-08 12:00:00', '2023-12-13', '14:00:00', '20:00:00', 11, 12),
(13, 'Torneo de Ajedrez', 'Participa en nuestro torneo de ajedrez y demuestra tus habilidades estratégicas.', NULL, '2023-11-09 14:15:00', '2023-12-16', '10:00:00', '13:00:00', 1, 13),
(14, 'Noche de Comedia Stand-up', 'Ríete a carcajadas con nuestros comediantes de stand-up en una noche divertida.', NULL, '2023-11-10 18:30:00', '2023-12-19', '21:00:00', '23:00:00', 1, 14),
(15, 'Exhibición de Autos Clásicos', 'Admira la elegancia de los autos clásicos en nuestra exhibición especial.', NULL, '2023-11-11 12:45:00', '2023-12-10', '10:00:00', '17:00:00', 8, 15),
(16, 'Festival de Danzas del Mundo', 'Disfruta de las danzas tradicionales de diferentes partes del mundo en nuestro festival.', NULL, '2023-11-12 16:00:00', '2023-12-15', '19:00:00', '22:00:00', 5, 16),
(17, 'Conferencia de Ciencia y Tecnología', 'Participa en nuestra conferencia sobre los últimos avances en ciencia y tecnología.', NULL, '2023-11-13 11:00:00', '2023-12-14', '14:30:00', '17:00:00', 4, 17),
(18, 'Noche de Flamenco', 'Vive la pasión del flamenco en nuestra noche dedicada a este arte.', NULL, '2023-12-14 19:30:00', '2023-11-16', '21:00:00', '23:30:00', 3, 18),
(19, 'Festival de Cortometrajes', 'Descubre nuevas historias en nuestro festival de cortometrajes independientes.', NULL, '2023-11-15 14:20:00', '2023-12-18', '18:00:00', '20:30:00', 7, 19),
(20, 'Competencia de Arte Urbano', 'Artistas urbanos compiten en vivo para crear obras impresionantes.', NULL, '2023-11-16 10:30:00', '2023-12-17', '12:00:00', '15:00:00', 8, 20);

INSERT INTO comentario (id_comentario, mensaje, id_publicacion, id_user, fecha_post) VALUES
(1, '¡Qué emocionante! Definitivamente estaré allí.', 1, 2, '2023-11-29 11:05:00'),
(2, '¿Habrá algo de comida? Me encantaría asistir.', 1, 5, '2023-11-29 13:30:00'),
(3, 'Increíble, ¿puedo llevar a mis hijos?', 2, 8, '2023-11-30 09:45:00'),
(4, '¡Me encantaría aprender a cocinar platos argentinos! ¿Hay algún costo?', 7, 3, '2023-11-01 16:20:00'),
(5, '¿Cuándo será la próxima proyección de cine indie?', 8, 6, '2023-11-02 14:10:00'),
(6, '¡No puedo esperar para la noche de tango! ¿Habrá clases?', 4, 9, '2023-11-03 20:45:00'),
(7, '¿Habrá libros de ciencia ficción en la feria del libro?', 11, 7, '2023-11-04 10:00:00'),
(8, '¿Cuántas rondas en el torneo de ajedrez? ¡Estoy emocionado!', 12, 10, '2023-11-05 12:30:00'),
(9, '¡Esa noche de comedia fue increíble! Me reí mucho.', 11, 4, '2023-11-06 22:15:00'),
(10, '¿Habrá oportunidades para fotografiar los autos clásicos?', 1, 1, '2023-11-07 18:00:00'),
(11, '¿Cuánto cuesta la entrada para el festival de danzas del mundo?', 16, 1, '2023-11-08 14:30:00'),
(12, 'Espero aprender mucho en la conferencia de ciencia y tecnología.', 17, 11, '2023-11-09 11:45:00'),
(13, '¡El flamenco siempre me ha fascinado! ¿Habrá artistas locales?', 12, 4, '2023-11-10 20:00:00'),
(14, '¿Podrías recomendarme algún cortometraje para ver en el festival?', 6, 1, '2023-11-11 16:20:00'),
(15, '¡Espero ver arte increíble en la competencia de arte urbano!', 20, 7, '2023-11-12 12:00:00'),
(16, '¿Qué tipo de música tocarán en el recital de jazz?', 11, 8, '2023-11-13 14:45:00'),
(17, '¡Mis hijos quieren participar en la carrera solidaria! ¿Pueden?', 5, 9, '2023-11-14 09:30:00'),
(18, '¿Habrá oportunidades para interactuar con los artistas en la exposición de arte?', 6, 1, '2023-11-15 17:00:00'),
(19, '¡La feria de artesanos es perfecta para comprar regalos únicos!', 2, 4, '2023-11-16 15:20:00'),
(20, '¿Habrá actividades para niños en el taller de cocina?', 7, 3, '2023-11-17 13:00:00');

