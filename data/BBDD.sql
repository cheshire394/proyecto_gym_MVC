CREATE DATABASE IF NOT EXISTS gimnasio;
USE gimnasio;

-- Tabla de monitores
CREATE TABLE IF NOT EXISTS MONITORES (
    dni VARCHAR(9) PRIMARY KEY,
    nombre VARCHAR(50),
    apellidos VARCHAR(100),
    fecha_nac DATE,
    telefono VARCHAR(15),
    email VARCHAR(100),
    cuenta_bancaria VARCHAR(24),
    funcion VARCHAR(20),
    sueldo DECIMAL(10,2),
    horas_extra INT,
    jornada INT
);

-- Tabla de disciplinas por monitor
CREATE TABLE IF NOT EXISTS DISCIPLINAS_MONITORES (
    dni_monitor VARCHAR(9),
    disciplina VARCHAR(50),
    FOREIGN KEY (dni_monitor) REFERENCES MONITORES(dni) ON DELETE CASCADE
);

-- Tabla de clases
CREATE TABLE IF NOT EXISTS CLASES (
    id_clase VARCHAR(20) PRIMARY KEY,
    dni_monitor VARCHAR(9),
    nombre_actividad VARCHAR(50),
    dia_semana VARCHAR(15),
    hora_inicio TIME,
    hora_fin TIME,
    FOREIGN KEY (dni_monitor) REFERENCES MONITORES(dni) ON DELETE SET NULL
);

-- Tabla de socios
CREATE TABLE IF NOT EXISTS SOCIOS (
    dni VARCHAR(9) PRIMARY KEY,
    nombre VARCHAR(50),
    apellidos VARCHAR(100),
    fecha_nac DATE,
    telefono VARCHAR(15),
    email VARCHAR(100),
    tarifa INT,
    cuenta_bancaria VARCHAR(24),
    fecha_alta DATE
);

-- Tabla de Recepcionistas
CREATE TABLE IF NOT EXISTS RECEPCIONISTAS (
    dni VARCHAR(9) PRIMARY KEY,
    nombre VARCHAR(50),
    apellidos VARCHAR(50),
    fecha_nac DATE,
    telefono VARCHAR(15),
    email VARCHAR(50),
    cuenta_bancaria VARCHAR(30),
    funcion VARCHAR(20),
    sueldo DECIMAL(10,2),
    horas_extra INT,
    jornada INT,
    password VARCHAR(255)
);

-- Insertar monitores
INSERT INTO MONITORES (dni, nombre, apellidos, fecha_nac, telefono, email, cuenta_bancaria, funcion, sueldo, horas_extra, jornada) VALUES
('50489319H', 'Laura', 'Rodriguez Vallejo', '1994-06-17', '650448327', 'laura_boxing@gmail.com', 'ES9123456789012345678901', 'monitor', 240, 0, 8),
('09626574Q', 'Carlos', 'Gómez Pérez', '1990-03-12', '691234567', 'carlos@gmail.com', 'ES9234567890123456789012', 'monitor', 420, 0, 14),
('55462206Y', 'Ana', 'Martínez López', '1988-09-23', '677123456', 'ana.judo@gmail.com', 'ES9345678901234567890123', 'monitor', 540, 0, 18);

-- Insertar disciplinas
INSERT INTO DISCIPLINAS_MONITORES (dni_monitor, disciplina) VALUES
('50489319H', 'boxeo'), ('50489319H', 'kickboxing'), ('50489319H', 'MMA'), ('50489319H', 'moay thai'), ('50489319H', 'capoira'),
('09626574Q', 'taekwondo'), ('09626574Q', 'karate'), ('09626574Q', 'boxeo'), ('09626574Q', 'MMA'), ('09626574Q', 'PRUEBA IAGO'),
('55462206Y', 'judo'), ('55462206Y', 'aikido'), ('55462206Y', 'capoira'), ('55462206Y', 'MMA');

-- Insertar clases
INSERT INTO CLASES (id_clase, dni_monitor, nombre_actividad, dia_semana, hora_inicio, hora_fin) VALUES
('lunes-12:00', '50489319H', 'kickboxing', 'lunes', '12:00', '14:00'),
('lunes-18:00', '50489319H', 'kickboxing', 'lunes', '18:00', '20:00'),
('miercoles-12:00', '50489319H', 'kickboxing', 'miercoles', '12:00', '14:00'),
('miercoles-18:00', '50489319H', 'kickboxing', 'miercoles', '18:00', '20:00'),
('viernes-10:00', '09626574Q', 'taekwondo', 'viernes', '10:00', '12:00'),
('viernes-12:00', '09626574Q', 'karate', 'viernes', '12:00', '14:00'),
('viernes-16:00', '09626574Q', 'taekwondo', 'viernes', '16:00', '18:00'),
('viernes-18:00', '09626574Q', 'karate', 'viernes', '18:00', '20:00'),
('sabado-12:00', '09626574Q', 'karate', 'sabado', '12:00', '14:00'),
('sabado-18:00', '09626574Q', 'taekwondo', 'sabado', '18:00', '20:00'),
('lunes-10:00', '09626574Q', 'MMA', 'lunes', '10:00', '12:00'),
('martes-10:00', '55462206Y', 'judo', 'martes', '10:00', '12:00'),
('martes-12:00', '55462206Y', 'aikido', 'martes', '12:00', '14:00'),
('martes-16:00', '55462206Y', 'judo', 'martes', '16:00', '18:00'),
('martes-18:00', '55462206Y', 'aikido', 'martes', '18:00', '20:00');

-- Insertar socios
INSERT INTO SOCIOS (dni, nombre, apellidos, fecha_nac, telefono, email, tarifa, cuenta_bancaria, fecha_alta) VALUES
('82709958A', 'Luis', 'Pérez Sánchez', '1988-10-12', '611987654', 'luis.perez@gym.com', 2, 'ES9121000418450200051332', '2024-12-10'),
('93330782W', 'Luis', 'López Jiménez', '1995-01-25', '612345987', 'sofia.lopez@gym.com', 3, NULL, '2024-12-10'),
('55534150Y', 'Javier', 'Rodríguez Fernández', '1990-07-04', '613456123', 'javier.rod@gym.com', 1, 'ES1421000418450200051415', '2024-12-10'),
('28539505R', 'Laura', 'Hernández Ruiz', '1986-11-30', '614567890', 'laura.hernandez@gym.com', 2, NULL, '2024-12-10'),
('84264364T', 'Ana', 'Martín García', '1992-04-20', '612345678', 'ana.martin@gym.com', 1, NULL, '2024-12-10'),
('64592462K', 'Carlos', 'Fernández López', '1985-08-15', '613456789', 'carlos.fernandez@gym.com', 2, 'ES9821000418450200051234', '2024-12-10'),
('04173382D', 'Sofía', 'García Pérez', '1990-03-10', '614567890', 'sofia.garcia@gym.com', 3, NULL, '2024-12-10'),
('37945157X', 'Diego', 'Jiménez Ruiz', '1988-12-01', '615678901', 'diego.jimenez@gym.com', 1, 'ES9121000418450200059876', '2024-12-10'),
('19517067A', 'Marta', 'Sánchez Martín', '1995-07-25', '616789012', 'marta.sanchez@gym.com', 2, NULL, '2024-12-10'),
('78419440C', 'Jorge', 'Rodríguez Gómez', '1983-05-30', '617890123', 'jorge.rodriguez@gym.com', 3, 'ES4721000418450200054567', '2024-12-10'),
('27988689N', 'Elena', 'Hernández López', '1991-11-15', '618901234', 'elena.hernandez@gym.com', 1, NULL, '2024-12-10'),
('98222874V', 'Pablo', 'Gómez Ruiz', '1987-09-05', '619012345', 'pablo.gomez@gym.com', 2, 'ES1421000418450200051234', '2024-12-10'),
('16416926D', 'Raúl', 'Martínez Sánchez', '1994-06-20', '620123456', 'raul.martinez@gym.com', 3, NULL, '2024-12-10'),
('94192599D', 'Clara', 'Vázquez Fernández', '1996-02-14', '621234567', 'clara.vazquez@gym.com', 1, 'ES9121000418450200057890', '2024-12-10')
('17304391L', 'Miguel', 'Gómez Martín', '1993-06-15', '615678234', 'miguel.gomez@gym.com', 3, 'ES4721000418450200051516', '2024-12-10');


-- Insertar datos en Recepcionistas
INSERT INTO RECEPCIONISTAS VALUES 
('16280029P', 'Iago', 'Fernandez Pereira', '1988-08-23', '677123456', 'Iago@gmail.com', 'ES9345678921244368890123', 'recepcionista', 1150, 0, 40, '$2y$10$GaYjfJDhGQFTGByJIjE2u.V1qb/HrYgVD34xh.oixO7GzxFamchwK');
