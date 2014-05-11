/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     11/05/2014 0:41:20                           */
/*==============================================================*/

/*==============================================================*/
/* Table: BENEFICIARIO                                          */
/*==============================================================*/
create table BENEFICIARIO
(
   ID                   int(11) not null,
   TITID                int(11),
   FECHANACIMIENTO      date,
   GENERO               int,
   ESTRATODOMICILIO     int,
   DIRECCION            varchar(100),
   BARRIO               varchar(30),
   MUNICIPIO            varchar(30),
   DEPTO                varchar(30),
   TELDOMICILIO         varchar(20),
   TELOFICINA           varchar(20),
   EPS                  varchar(30),
   NOHIJOS              int,
   OCUPACION            int,
   ESTADOCIVIL          int,
   primary key (ID)
);

/*==============================================================*/
/* Table: CONTACTO                                              */
/*==============================================================*/
create table CONTACTO
(
   NOMBRECOMPLETO       varchar(100),
   PARENTESCO           varchar(50),
   INDICATIVO           varchar(5),
   TELDOMICILIO         varchar(15),
   TELMOVIL             varchar(15),
   ID                   int(11) not null,
   primary key (ID)
);

/*==============================================================*/
/* Table: CONTRATO                                              */
/*==============================================================*/
create table CONTRATO
(
   ID                   int(11) not null,
   TITID                int(11),
   PLANID               int(11),
   TIPOCONTRATO         int,
   FECHA                date,
   TIPOPLAN             int,
   primary key (ID)
);

/*==============================================================*/
/* Table: COSTOPLAN                                             */
/*==============================================================*/
create table COSTOPLAN
(
   ID                   int(11) not null,
   PLANID               int(11),
   COSTOAFILIACION      int(11),
   COSTOPAGO            int(11),
   FECHADESDE           date,
   FECHAHASTA           date,
   primary key (ID)
);

/*==============================================================*/
/* Table: DOCUMENTO                                             */
/*==============================================================*/
create table DOCUMENTO
(
   ID                   int(11) not null,
   EMPID                int(11),
   NUMERO               int(11) not null,
   TIPO                 int not null,
   primary key (ID)
);

/*==============================================================*/
/* Table: PAGO                                                  */
/*==============================================================*/
create table PAGO
(
   ID                   int(11) not null,
   RECID                int(11) not null,
   TITID                int(11),
   VALOR                int(11),
   FECHA                date,
   primary key (ID)
);

/*==============================================================*/
/* Table: PLAN                                                  */
/*==============================================================*/
create table PLAN
(
   FORMAPAGO            int,
   PERIODICIDAD         int,
   TIPOPLAN             int,
   NOMBRECONVENIO       varchar(30),
   ID                   int(11) not null,
   primary key (ID)
);

/*==============================================================*/
/* Table: TITULAR                                               */
/*==============================================================*/
create table TITULAR
(
   ID                   int(11) not null,
   PAIS                 varchar(30),
   CIUDAD               varchar(30),
   BENEFICIARIO         bool,
   FECHANACIMIENTO      date,
   GENERO               int,
   COBRODIRECCION       varchar(100),
   COBROBARRIO          varchar(50),
   COBROMUNICIPIO       varchar(30),
   COBRODEPTO           varchar(30),
   DOMIDIRECCION        varchar(100),
   DOMIBARRIO           varchar(50),
   DOMIMUNICIPIO        varchar(30),
   DOMIDEPTO            varchar(30),
   TELDOMICILIO         varchar(20),
   TELOFICINA           varchar(20),
   NOHIJOS              int,
   NODEPENDIENTES       int,
   ESTRATO              int,
   ESTADOCIVIL          int,
   OCUPACION            int,
   EPS                  varchar(30),
   COMOUBICOSERVICIO    int,
   PERMITEUSODATOS      bool,
   primary key (ID)
);


-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generaciÃ³n: 11-05-2014 a las 16:18:53
-- VersiÃ³n del servidor: 5.5.24-log
-- VersiÃ³n de PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `clientes`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `ip_address` varchar(16) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `login_attempts`
--

CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(40) COLLATE utf8_bin NOT NULL,
  `login` varchar(50) COLLATE utf8_bin NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `identificadorRol` int(11) NOT NULL AUTO_INCREMENT,
  `nombreRol` varchar(124) NOT NULL,
  `descripcionRol` varchar(1048) DEFAULT NULL,
  PRIMARY KEY (`identificadorRol`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`identificadorRol`, `nombreRol`, `descripcionRol`) VALUES
(1, 'Administrador', NULL),
(2, 'Digitador', NULL),
(3, 'Cajero', NULL),
(4, 'Consultor', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `identificadorRol` int(11) NOT NULL DEFAULT '4',
  `username` varchar(50) COLLATE utf8_bin NOT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL,
  `email` varchar(100) COLLATE utf8_bin NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '1',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `ban_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `new_password_key` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `new_password_requested` datetime DEFAULT NULL,
  `new_email` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `new_email_key` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   NOMBRES              varchar(50),
   APELLIDOS            varchar(50),
   TIPODOC              int,
   NODOCUMENTO          varchar(20),
   TELMOVIL             varchar(15),
   TIPOPERSONA          int,
  PRIMARY KEY (`id`),
  KEY `fk_roles_to_users` (`identificadorRol`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=52 ;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `identificadorRol`, `username`, `password`, `email`, `activated`, `banned`, `ban_reason`, `new_password_key`, `new_password_requested`, `new_email`, `new_email_key`, `last_ip`, `last_login`, `created`, `modified`) VALUES
(1, 1, 'Administrador', '$2a$08$/b4PsxYlq04kkbzuQwNTxuhofruTRDxsouZUoY45lgDgONZonx5n2', 'administrador@gmail.com', 1, 0, NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2014-05-11 15:26:50', '2014-05-03 17:16:39', '2014-05-11 15:57:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_autologin`
--

CREATE TABLE IF NOT EXISTS `user_autologin` (
  `key_id` char(32) COLLATE utf8_bin NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`key_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_profiles`
--

CREATE TABLE IF NOT EXISTS `user_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `country` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=49 ;

--
-- Volcado de datos para la tabla `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `user_id`, `country`, `website`) VALUES
(1, 1, NULL, NULL);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_roles_to_users` FOREIGN KEY (`identificadorRol`) REFERENCES `roles` (`identificadorRol`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;



alter table BENEFICIARIO add constraint FK_usersBENEFICIARIO foreign key (ID)
      references users (ID) on delete restrict on update restrict;

alter table DOCUMENTO add constraint FK_usersDOCUMENTO foreign key (EMPID)
      references users (ID) on delete restrict on update restrict;

alter table TITULAR add constraint FK_usersTITULAR foreign key (ID)
      references users (ID) on delete restrict on update restrict;
	  
alter table BENEFICIARIO add constraint FK_TITULARBENEFICIARIO foreign key (TITID)
      references TITULAR (ID) on delete restrict on update restrict;

alter table CONTACTO add constraint FK_TITULARCONTACTO foreign key (ID)
      references TITULAR (ID) on delete restrict on update restrict;

alter table CONTRATO add constraint FK_CONTRATOTITULAR foreign key (TITID)
      references TITULAR (ID) on delete restrict on update restrict;

alter table CONTRATO add constraint FK_DOCUMENTOCONTRATO foreign key (ID)
      references DOCUMENTO (ID) on delete restrict on update restrict;

alter table CONTRATO add constraint FK_PLANCONVENIO foreign key (PLANID)
      references PLAN (ID) on delete restrict on update restrict;

alter table COSTOPLAN add constraint FK_PLANCOSTO foreign key (PLANID)
      references PLAN (ID) on delete restrict on update restrict;


	  
alter table PAGO add constraint FK_DOCUMENTOPAGO foreign key (RECID)
      references DOCUMENTO (ID) on delete restrict on update restrict;

alter table PAGO add constraint FK_TITULARPAGO foreign key (TITID)
      references TITULAR (ID) on delete restrict on update restrict;



