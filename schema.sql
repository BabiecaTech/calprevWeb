SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
--
-- Base de datos: `calendario`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `login`
--


DROP TABLE IF EXISTS login; -- login
CREATE TABLE login (
  id int(11) NOT NULL AUTO_INCREMENT,
  user varchar(50) NOT NULL,
  password varchar(25) NOT NULL,
  email varchar(100) NOT NULL,
  pasadmin varchar(25) NOT NULL,
  rol int(3) NOT NULL,
  id_finca int(10),
  CONSTRAINT login_pk PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS events; -- events
CREATE TABLE events (
  id int(11) NOT NULL,
  title varchar(200) DEFAULT NULL,
  color varchar(10) DEFAULT NULL,
  start date DEFAULT NULL,
  end date DEFAULT NULL,
  id_user int(11) NOT NULL,
  CONSTRAINT events_pk PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS fincas; -- fincas
CREATE TABLE fincas (
  id int(10) NOT NULL,
  nombre varchar(200) NOT NULL,
  hectareas int(10) NOT NULL,
  latitud float,
  longitud float,
  id_tipo int(10),
  CONSTRAINT finca_pk PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS vinedos; -- viñedos
CREATE TABLE vinedos (
  id int(10) NOT NULL,
  nombre varchar(200) NOT NULL,
  CONSTRAINT vinedo_pk PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE events ADD FOREIGN KEY(id_user) REFERENCES login(id);
ALTER TABLE login ADD FOREIGN KEY(id_finca) REFERENCES fincas(id);
ALTER TABLE fincas ADD FOREIGN KEY(id_tipo) REFERENCES vinedos(id);
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
(1, 'finca prueba', 20, -33.1867, -68.3929, 1);

INSERT INTO login (id, user, password, email, pasadmin, rol) VALUES
(1, 'Administrador', '', 'admin@gmail.com', '123456', 1),
(2, 'Sergio', '12345', 'sergio@gmail.com', '', 2),
(3, 'Federico', 'gimli777', 'fedeaguirre@gmail.com', '', 2);

INSERT INTO events (id, title, color, start, end, id_user) VALUES
(1, 'Tarea 1', '#167EAF', '2018-05-09', '2018-05-15', 3),
(2, 'Tarea 1', '#167EAF', '2018-05-09', '2018-05-12', 2);