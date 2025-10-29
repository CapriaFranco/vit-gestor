CREATE TABLE IF NOT EXISTS equipos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  curso ENUM('1ro', '2do', '3ro', '4to', '5to', '6to', '7mo') NOT NULL,
  division ENUM('A', 'B', 'C', '1ra', '2da') NOT NULL,
  nombre_equipo VARCHAR(100) NOT NULL,
  sistema_juego ENUM('6:0', '4:2', '5:1') NOT NULL,
  tipo_cuatro_dos ENUM('c', 'o') DEFAULT NULL,
  color_remera VARCHAR(50) NOT NULL,
  capitan VARCHAR(100) NOT NULL,
  telefono VARCHAR(20) DEFAULT NULL,
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS integrantes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_equipo INT NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  posicion ENUM('Punta', 'Opuesto', 'Central', 'Armador', 'Libero') DEFAULT NULL,
  FOREIGN KEY (id_equipo) REFERENCES equipos(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS codigos_acceso (
  id INT AUTO_INCREMENT PRIMARY KEY,
  codigo VARCHAR(9) NOT NULL UNIQUE,
  usado BOOLEAN DEFAULT 0,
  id_equipo INT DEFAULT NULL,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_equipo) REFERENCES equipos(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS admin (
  id INT AUTO_INCREMENT PRIMARY KEY,
  password_hash VARCHAR(255) NOT NULL,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
