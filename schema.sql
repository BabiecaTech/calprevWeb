SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
--
-- Base de datos: `calendario`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `login`
--
DROP TABLE IF EXISTS events; -- events
CREATE TABLE events (
  id int(11) NOT NULL,
  title varchar(200) DEFAULT NULL,
  color varchar(10) DEFAULT NULL,
  start date DEFAULT NULL,
  end date DEFAULT NULL,
  id_user int(10) NOT NULL,
  CONSTRAINT events_pk PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS usuariios; -- users
CREATE TABLE usuarios (
  id int(10) NOT NULL AUTO_INCREMENT,
  user varchar(50) NOT NULL,
  password varchar(25) NOT NULL,
  email varchar(100) NOT NULL,
  pasadmin varchar(25) NOT NULL,
  rol int(3) NOT NULL,
  id_finca int(10),
  CONSTRAINT usuario_pk PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS fincas; -- fincas
CREATE TABLE fincas (
  id int(10) NOT NULL,
  nombre varchar(100) NOT NULL,
  hectareas int(10) NOT NULL,
  latitud float,
  longitud float,
  id_tipo int(10),
  CONSTRAINT finca_pk PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS ;
CREATE TABLE fincas (
  id int(10) NOT NULL,
  nombre varchar(100) NOT NULL,
  hectareas int(10) NOT NULL,
  latitud float,
  longitud float,
  id_tipo int(10),
  CONSTRAINT finca_pk PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS vinedos; -- viñedos
CREATE TABLE vinedos (
  id int(10) NOT NULL,
  nombre varchar(100) NOT NULL,
  CONSTRAINT vinedo_pk PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS finvin; -- viñedos
CREATE TABLE finvin (
  id int(20) NOT NULL,
  id_finca int(10) NOT NULL,
  id_vinedo int(10) NOT NULL,
  cant_hectarea int(10) NOT NULL,
  CONSTRAINT finvin_pk PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS prodias; -- pronosticos dia
CREATE TABLE prodias (
  fecha date NOT NULL,
  temperaturaMin int(4),
  temperaturaMax int(4),
  humedad int(4),
  presion int(5),
  viento int(4),
  probLluvia int(4),
  faseLunar int(4),
  id_finca int(10) NOT NULL,
  CONSTRAINT pdias_pk PRIMARY KEY (fecha)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS prohoras; -- pronosticos horas
CREATE TABLE prohoras (
  fecha date NOT NULL,
  hora int (2) NOT NULL,
  temperatura int(4),
  humedad int(4),
  presion int(5),
  viento int(4),
  probLluvia int(4),
  CONSTRAINT prohoras_pk PRIMARY KEY (fecha,hora)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE events ADD FOREIGN KEY(id_user) REFERENCES usuarios(id);
ALTER TABLE usuarios ADD FOREIGN KEY(id_finca) REFERENCES fincas(id);
ALTER TABLE fincas ADD FOREIGN KEY(id_tipo) REFERENCES vinedos(id);
ALTER TABLE finvin ADD FOREIGN KEY(id_finca) REFERENCES fincas(id);
ALTER TABLE finvin ADD FOREIGN KEY(id_vinedo) REFERENCES vinedos(id);
ALTER TABLE prodias ADD FOREIGN KEY(id_finca) REFERENCES fincas(id);
ALTER TABLE prohoras ADD FOREIGN KEY(fecha) REFERENCES prodias(fecha);
--
-- insertar datos
--

INSERT INTO vinedos (id,nombre) VALUES (1,'malbec'),
(2,'cabernet'),
(3,'bonarda'),
(4,'syrah'),
(5,'tempranillo'),
(6,'tintasgenericas blancas'),
(7,'chardonnay'),
(8,'soivignon');

INSERT INTO fincas (id, nombre, hectareas, latitud, longitud, id_tipo) VALUES
(1, 'finca prueba', 20, -33.1867, -68.3929, 4);

INSERT INTO usuarios (id, user, password, email, pasadmin, rol, id_finca) VALUES
(1, 'Administrador', '', 'admin@gmail.com', '123456', 1,1),
(2, 'Sergio', '12345', 'sergio@gmail.com', '', 2,1),
(3, 'Federico', 'gimli777', 'fedeaguirre@gmail.com', '', 2,1);

INSERT INTO events (id, title, color, start, end, id_user) VALUES
(1, 'Tarea 1', '#167EAF', '2018-05-09', '2018-05-15', 3),
(2, 'Tarea 1', '#167EAF', '2018-05-09', '2018-05-12', 2);