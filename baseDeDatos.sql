SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';


DROP SCHEMA IF EXISTS `etechhelp_db` ;
CREATE SCHEMA IF NOT EXISTS `etechhelp_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ;
USE `etechhelp_db` ;

-- Table `Rol`

DROP TABLE IF EXISTS `Rol` ;

CREATE TABLE IF NOT EXISTS `Rol` (
`idRol` TINYINT NOT NULL AUTO_INCREMENT,
`nombreRol` VARCHAR(20) NOT NULL,
PRIMARY KEY (`idRol`))
ENGINE = InnoDB;



-- Table `tipoAprendizaje`

DROP TABLE IF EXISTS `tipoAprendizaje` ;

CREATE TABLE IF NOT EXISTS `tipoAprendizaje` (
`idtipoAprendizaje` TINYINT NOT NULL AUTO_INCREMENT,
`nombreAprendizaje` VARCHAR(20) NOT NULL,
PRIMARY KEY (`idtipoAprendizaje`))
ENGINE = InnoDB;



-- Table `ETE`

DROP TABLE IF EXISTS `ETE` ;

CREATE TABLE IF NOT EXISTS `ETE` (
`idETE` TINYINT NOT NULL AUTO_INCREMENT,
`nombre` VARCHAR(50) NOT NULL,
PRIMARY KEY (`idETE`))
ENGINE = InnoDB;



-- Table `Usuario` 

DROP TABLE IF EXISTS `Usuario` ;

CREATE TABLE IF NOT EXISTS `Usuario` (
`idUsuario` CHAR(36) NOT NULL,
`nombre` VARCHAR(50) NOT NULL,
`apellidoPaterno` VARCHAR(50) NOT NULL,
`apellidoMaterno` VARCHAR(50) NULL, -- Opcional (Acepta NULL según requerimiento)
`correo` VARCHAR(100) NOT NULL,
`contraseña` VARCHAR(255) NOT NULL,
`Rol_idRol` TINYINT NOT NULL,
`tipoAprendizaje_idtipoAprendizaje` TINYINT NOT NULL,
PRIMARY KEY (`idUsuario`),
UNIQUE INDEX `correo_UNIQUE` (`correo` ASC) VISIBLE,
INDEX `fk_Usuario_Rol_idx` (`Rol_idRol` ASC) VISIBLE,
INDEX `fk_Usuario_tipoAprendizaje1_idx` (`tipoAprendizaje_idtipoAprendizaje` ASC) VISIBLE,
CONSTRAINT `fk_Usuario_Rol`
    FOREIGN KEY (`Rol_idRol`)
    REFERENCES `Rol` (`idRol`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
CONSTRAINT `fk_Usuario_tipoAprendizaje1`
    FOREIGN KEY (`tipoAprendizaje_idtipoAprendizaje`)
    REFERENCES `tipoAprendizaje` (`idtipoAprendizaje`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



-- Table `cicloEscolar` 

DROP TABLE IF EXISTS `cicloEscolar` ;

CREATE TABLE IF NOT EXISTS `cicloEscolar` (
`idcicloEscolar` TINYINT NOT NULL AUTO_INCREMENT,
`anioInicio` YEAR NOT NULL,
`anioFin` YEAR NOT NULL,
`ETE_idETE` TINYINT NOT NULL,
PRIMARY KEY (`idcicloEscolar`),
INDEX `fk_cicloEscolar_ETE1_idx` (`ETE_idETE` ASC) VISIBLE,
CONSTRAINT `fk_cicloEscolar_ETE1`
    FOREIGN KEY (`ETE_idETE`)
    REFERENCES `ETE` (`idETE`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



-- Table `Materia` 

DROP TABLE IF EXISTS `Materia` ;

CREATE TABLE IF NOT EXISTS `Materia` (
`idMateria` TINYINT NOT NULL AUTO_INCREMENT,
`nombre` VARCHAR(50) NOT NULL,
`tipoMateria` ENUM('curricular', 'extracurricular') NOT NULL,
`ETE_idETE` TINYINT NOT NULL,
PRIMARY KEY (`idMateria`),
INDEX `fk_Materia_ETE1_idx` (`ETE_idETE` ASC) VISIBLE,
CONSTRAINT `fk_Materia_ETE1`
    FOREIGN KEY (`ETE_idETE`)
    REFERENCES `ETE` (`idETE`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



-- Table `Inscripcion` 

DROP TABLE IF EXISTS `Inscripcion` ;

CREATE TABLE IF NOT EXISTS `Inscripcion` (
`idInscripcion` CHAR(36) NOT NULL,
`Grupo_idGrupo` TINYINT NOT NULL,
`cicloEscolar_idcicloEscolar` TINYINT NOT NULL,
`Materia_idMateria` TINYINT NOT NULL,
PRIMARY KEY (`idInscripcion`),
INDEX `fk_Inscripcion_cicloEscolar1_idx` (`cicloEscolar_idcicloEscolar` ASC) VISIBLE,
INDEX `fk_Inscripcion_Materia1_idx` (`Materia_idMateria` ASC) VISIBLE,
CONSTRAINT `fk_Inscripcion_cicloEscolar1`
    FOREIGN KEY (`cicloEscolar_idcicloEscolar`)
    REFERENCES `cicloEscolar` (`idcicloEscolar`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
CONSTRAINT `fk_Inscripcion_Materia1`
    FOREIGN KEY (`Materia_idMateria`)
    REFERENCES `Materia` (`idMateria`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



-- Table `Grupo`

DROP TABLE IF EXISTS `Grupo` ;

CREATE TABLE IF NOT EXISTS `Grupo` (
`idGrupo` TINYINT NOT NULL AUTO_INCREMENT,
`nombreGrupo` VARCHAR(20) NOT NULL,
`Inscripcion_idInscripcion` CHAR(36) NOT NULL,
PRIMARY KEY (`idGrupo`),
INDEX `fk_Grupo_Inscripcion1_idx` (`Inscripcion_idInscripcion` ASC) VISIBLE,
CONSTRAINT `fk_Grupo_Inscripcion1`
    FOREIGN KEY (`Inscripcion_idInscripcion`)
    REFERENCES `Inscripcion` (`idInscripcion`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



-- Table `Clase` 

DROP TABLE IF EXISTS `Clase` ;

CREATE TABLE IF NOT EXISTS `Clase` (
`idClase` INT NOT NULL AUTO_INCREMENT,
`horaInicio` TIME NOT NULL,
`horaFin` TIME NOT NULL,
`salon` VARCHAR(45) NOT NULL,
`dia` DATE NOT NULL,
`Grupo_idGrupo` TINYINT NOT NULL,
PRIMARY KEY (`idClase`),
INDEX `fk_Clase_Grupo1_idx` (`Grupo_idGrupo` ASC) VISIBLE,
CONSTRAINT `fk_Clase_Grupo1`
    FOREIGN KEY (`Grupo_idGrupo`)
    REFERENCES `Grupo` (`idGrupo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



-- Table `HorarioMateria`

DROP TABLE IF EXISTS `HorarioMateria` ;

CREATE TABLE IF NOT EXISTS `HorarioMateria` (
`idHorarioMateria` CHAR(36) NOT NULL,
`Clase_idClase` INT NOT NULL,
`Materia_idMateria` TINYINT NOT NULL,
`cicloEscolar_idcicloEscolar` TINYINT NOT NULL,
PRIMARY KEY (`idHorarioMateria`),
INDEX `fk_HorarioMateria_Clase1_idx` (`Clase_idClase` ASC) VISIBLE,
INDEX `fk_HorarioMateria_Materia1_idx` (`Materia_idMateria` ASC) VISIBLE,
INDEX `fk_HorarioMateria_cicloEscolar1_idx` (`cicloEscolar_idcicloEscolar` NOT NULL) VISIBLE,
CONSTRAINT `fk_HorarioMateria_Clase1`
    FOREIGN KEY (`Clase_idClase`)
    REFERENCES `Clase` (`idClase`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
CONSTRAINT `fk_HorarioMateria_Materia1`
    FOREIGN KEY (`Materia_idMateria`)
    REFERENCES `Materia` (`idMateria`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
CONSTRAINT `fk_HorarioMateria_cicloEscolar1`
    FOREIGN KEY (`cicloEscolar_idcicloEscolar`)
    REFERENCES `cicloEscolar` (`idcicloEscolar`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



-- Table `Modulo`

DROP TABLE IF EXISTS `Modulo` ;

CREATE TABLE IF NOT EXISTS `Modulo` (
`idModulo` INT NOT NULL AUTO_INCREMENT,
`nombreModulo` VARCHAR(50) NOT NULL,
`numModulo` INT NOT NULL,
`descripcion` TEXT NULL,
PRIMARY KEY (`idModulo`))
ENGINE = InnoDB;



-- Table `Actividad`

DROP TABLE IF EXISTS `Actividad` ;

CREATE TABLE IF NOT EXISTS `Actividad` (
`idActividad` CHAR(36) NOT NULL,
`titulo` VARCHAR(100) NOT NULL,
`fechaCreacion` DATETIME NOT NULL,
`fechaLimite` DATETIME NOT NULL,
`descripcion` TEXT NULL,
`archivoAdjunto` VARCHAR(255) NULL,
`puntosMax` DECIMAL(5,2) NOT NULL,
`Modulo_idModulo` INT NOT NULL,
`Clase_idClase` INT NOT NULL,
PRIMARY KEY (`idActividad`),
INDEX `fk_Actividad_Modulo1_idx` (`Modulo_idModulo` ASC) VISIBLE,
INDEX `fk_Actividad_Clase1_idx` (`Clase_idClase` ASC) VISIBLE,
CONSTRAINT `fk_Actividad_Modulo1`
    FOREIGN KEY (`Modulo_idModulo`)
    REFERENCES `Modulo` (`idModulo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
CONSTRAINT `fk_Actividad_Clase1`
    FOREIGN KEY (`Clase_idClase`)
    REFERENCES `Clase` (`idClase`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



-- Table `entrega`

DROP TABLE IF EXISTS `entrega` ;

CREATE TABLE IF NOT EXISTS `entrega` (
`idEntrega` CHAR(36) NOT NULL,
`fechaCreacion` DATETIME NOT NULL,
`archivo` VARCHAR(255) NOT NULL,
`calificacion` DECIMAL(5,2) NULL,
`Actividad_idActividad` CHAR(36) NOT NULL,
`Usuario_idUsuario` CHAR(36) NOT NULL,
PRIMARY KEY (`idEntrega`),
INDEX `fk_entrega_Actividad1_idx` (`Actividad_idActividad` ASC) VISIBLE,
INDEX `fk_entrega_Usuario1_idx` (`Usuario_idUsuario` ASC) VISIBLE,
CONSTRAINT `fk_entrega_Actividad1`
    FOREIGN KEY (`Actividad_idActividad`)
    REFERENCES `Actividad` (`idActividad`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
CONSTRAINT `fk_entrega_Usuario1`
    FOREIGN KEY (`Usuario_idUsuario`)
    REFERENCES `Usuario` (`idUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



-- Table `Recurso`

DROP TABLE IF EXISTS `Recurso` ;

CREATE TABLE IF NOT EXISTS `Recurso` (
`idRecurso` CHAR(36) NOT NULL,
`titulo` VARCHAR(100) NOT NULL,
`descripcion` TEXT NULL,
`archivo` VARCHAR(255) NOT NULL,
`fechaSubida` DATETIME NOT NULL,
`Actividad_idActividad` CHAR(36) NOT NULL,
`Usuario_idUsuario` CHAR(36) NOT NULL,
PRIMARY KEY (`idRecurso`),
INDEX `fk_Recurso_Actividad1_idx` (`Actividad_idActividad` ASC) VISIBLE,
INDEX `fk_Recurso_Usuario1_idx` (`Usuario_idUsuario` ASC) VISIBLE,
CONSTRAINT `fk_Recurso_Actividad1`
    FOREIGN KEY (`Actividad_idActividad`)
    REFERENCES `Actividad` (`idActividad`)
    ON DELETE NO ACTION
    ON ACTION,
CONSTRAINT `fk_Recurso_Usuario1`
    FOREIGN KEY (`Usuario_idUsuario`)
    REFERENCES `Usuario` (`idUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



-- Table `Anuncio`

DROP TABLE IF EXISTS `Anuncio` ;

CREATE TABLE IF NOT EXISTS `Anuncio` (
`idAnuncio` CHAR(36) NOT NULL,
`titulo` VARCHAR(100) NOT NULL,
`fecha` DATETIME NOT NULL,
`descripcion` TEXT NOT NULL,
`salon` VARCHAR(45) NULL,
`horaInicio` TIME NULL,
`horaFinal` TIME NULL,
`Clase_idClase` INT NOT NULL,
PRIMARY KEY (`idAnuncio`),
INDEX `fk_Anuncio_Clase1_idx` (`Clase_idClase` ASC) VISIBLE,
CONSTRAINT `fk_Anuncio_Clase1`
    FOREIGN KEY (`Clase_idClase`)
    REFERENCES `Clase` (`idClase`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



-- Table `Asesoria`

DROP TABLE IF EXISTS `Asesoria` ;

CREATE TABLE IF NOT EXISTS `Asesoria` (
`idAsesoria` CHAR(36) NOT NULL,
`titulo` VARCHAR(50) NOT NULL,
`fecha` DATETIME NOT NULL,
`descripcion` TEXT NOT NULL,
`salon` VARCHAR(45) NULL,
`horaInicio` TIME NULL,
`horaFinal` TIME NULL,
`dia` DATE NULL,
`Usuario_idUsuario` CHAR(36) NOT NULL,
PRIMARY KEY (`idAsesoria`),
INDEX `fk_Asesoria_Usuario1_idx` (`Usuario_idUsuario` ASC) VISIBLE,
CONSTRAINT `fk_Asesoria_Usuario1`
    FOREIGN KEY (`Usuario_idUsuario`)
    REFERENCES `Usuario` (`idUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



-- Table `Asistencia` 

DROP TABLE IF EXISTS `Asistencia` ;

CREATE TABLE IF NOT EXISTS `Asistencia` (
`fecha` DATE NOT NULL,
`campo` VARCHAR(50) NULL,
`Usuario_idUsuario` CHAR(36) NOT NULL,
`Inscripcion_idInscripcion` CHAR(36) NOT NULL,
PRIMARY KEY (`fecha`, `Usuario_idUsuario`),
INDEX `fk_Asistencia_Usuario1_idx` (`Usuario_idUsuario` ASC) VISIBLE,
INDEX `fk_Asistencia_Inscripcion1_idx` (`Inscripcion_idInscripcion` ASC) VISIBLE,
CONSTRAINT `fk_Asistencia_Usuario1`
    FOREIGN KEY (`Usuario_idUsuario`)
    REFERENCES `Usuario` (`idUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
CONSTRAINT `fk_Asistencia_Inscripcion1`
    FOREIGN KEY (`Inscripcion_idInscripcion`)
    REFERENCES `Inscripcion` (`idInscripcion`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



-- Table `Comentario`

DROP TABLE IF EXISTS `Comentario` ;

CREATE TABLE IF NOT EXISTS `Comentario` (
`idComentario` CHAR(36) NOT NULL,
`fechaCreacion` DATETIME NOT NULL,
`archivo` VARCHAR(255) NULL,
`Actividad_idActividad` CHAR(36) NOT NULL,
PRIMARY KEY (`idComentario`),
INDEX `fk_Comentario_Actividad1_idx` (`Actividad_idActividad` ASC) VISIBLE,
CONSTRAINT `fk_Comentario_Actividad1`
    FOREIGN KEY (`Actividad_idActividad`)
    REFERENCES `Actividad` (`idActividad`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



-- Table `Cuestionario`

DROP TABLE IF EXISTS `Cuestionario` ;

CREATE TABLE IF NOT EXISTS `Cuestionario` (
`idCuestionario` CHAR(36) NOT NULL,
`tiempoLimite` TINYINT NULL,
`numeroPreguntas` TINYINT NOT NULL,
`ordenAleatorio` TINYINT(1) NOT NULL,
`intentosPermitidos` TINYINT NULL,
`entrega_idEntrega` CHAR(36) NOT NULL,
`Actividad_idActividad` CHAR(36) NOT NULL,
PRIMARY KEY (`idCuestionario`),
INDEX `fk_Cuestionario_entrega1_idx` (`entrega_idEntrega` ASC) VISIBLE,
INDEX `fk_Cuestionario_Actividad1_idx` (`Actividad_idActividad` ASC) VISIBLE,
CONSTRAINT `fk_Cuestionario_entrega1`
    FOREIGN KEY (`entrega_idEntrega`)
    REFERENCES `entrega` (`idEntrega`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
CONSTRAINT `fk_Cuestionario_Actividad1`
    FOREIGN KEY (`Actividad_idActividad`)
    REFERENCES `Actividad` (`idActividad`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



-- Table `Calificacion`

DROP TABLE IF EXISTS `Calificacion` ;

CREATE TABLE IF NOT EXISTS `Calificacion` (
`Calificacion` DECIMAL(5,2) NOT NULL,
`entrega_idEntrega` CHAR(36) NOT NULL,
`Usuario_idUsuario` CHAR(36) NOT NULL,
INDEX `fk_Calificacion_entrega1_idx` (`entrega_idEntrega` ASC) VISIBLE,
INDEX `fk_Calificacion_Usuario1_idx` (`Usuario_idUsuario` ASC) VISIBLE,
CONSTRAINT `fk_Calificacion_entrega1`
    FOREIGN KEY (`entrega_idEntrega`)
    REFERENCES `entrega` (`idEntrega`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
CONSTRAINT `fk_Calificacion_Usuario1`
    FOREIGN KEY (`Usuario_idUsuario`)
    REFERENCES `Usuario` (`idUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;