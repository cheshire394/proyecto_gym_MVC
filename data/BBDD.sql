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
    funcion VARCHAR(20) DEFAULT 'monitor',
    sueldo DECIMAL(10,2),
    jornada INT
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
    nombre VARCHAR(50) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    fecha_nac DATE NOT NULL,
    telefono VARCHAR(15),
    email VARCHAR(100),
    tarifa INT NOT NULL,
    cuota_mensual DECIMAL(10,2),
    cuenta_bancaria VARCHAR(24),
    fecha_alta DATE
);


-- Tabla intermedia para la relación N:M entre SOCIOS y CLASES
CREATE TABLE IF NOT EXISTS SOCIOS_CLASES (
    id_socio_clase INT AUTO_INCREMENT PRIMARY KEY,
    dni_socio VARCHAR(9) NOT NULL,
    id_clase VARCHAR(20) NOT NULL,
    FOREIGN KEY (dni_socio) REFERENCES SOCIOS(dni) ON DELETE CASCADE,
    FOREIGN KEY (id_clase) REFERENCES CLASES(id_clase) ON DELETE CASCADE,
    UNIQUE (dni_socio, id_clase) -- Evita inscripciones duplicadas
);

-- Tabla de Recepcionistas
CREATE TABLE IF NOT EXISTS RECEPCIONISTAS (
    dni VARCHAR(9) PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellidos VARCHAR(50) NOT NULL,
    fecha_nac DATE,
    telefono VARCHAR(15),
    email VARCHAR(50),
    cuenta_bancaria VARCHAR(30),
    funcion VARCHAR(20) DEFAULT 'recepcionista',
    sueldo DECIMAL(10,2),
    jornada INT,
    password VARCHAR(255)
);


-- Insertar monitores
INSERT INTO MONITORES (dni, nombre, apellidos, fecha_nac, telefono, email, cuenta_bancaria, funcion, sueldo, jornada) VALUES
('50489319H', 'Laura', 'Rodriguez Vallejo', '1994-06-17', '650448327', 'laura_boxing@gmail.com', 'ES9123456789012345678901', 'monitor', 240, 8),
('09626574Q', 'Carlos', 'Gómez Pérez', '1990-03-12', '691234567', 'carlos@gmail.com', 'ES9234567890123456789012', 'monitor', 420, 14),
('55462206Y', 'Ana', 'Martínez López', '1988-09-23', '677123456', 'ana.Judo@gmail.com', 'ES9345678901234567890123', 'monitor', 540, 18);

-- Insertar clases
INSERT INTO CLASES (id_clase, dni_monitor, nombre_actividad, dia_semana, hora_inicio, hora_fin) VALUES
('lunes-10:00', '09626574Q', 'MMA', 'lunes', '10:00', '12:00'),
('lunes-12:00', '50489319H', 'Kickboxing', 'lunes', '12:00', '14:00'),
('lunes-18:00', '50489319H', 'Kickboxing', 'lunes', '18:00', '20:00'),
('martes-10:00', '55462206Y', 'Judo', 'martes', '10:00', '12:00'),
('martes-12:00', '55462206Y', 'Aikido', 'martes', '12:00', '14:00'),
('martes-16:00', '55462206Y', 'Judo', 'martes', '16:00', '18:00'),
('martes-18:00', '55462206Y', 'Aikido', 'martes', '18:00', '20:00'),
('miercoles-10:00', '50489319H', 'Boxeo', 'miercoles', '10:00', '12:00'),
('miercoles-12:00', '09626574Q', 'MMA', 'miercoles', '12:00', '14:00'),
('miercoles-16:00', '55462206Y', 'Boxeo', 'miercoles', '16:00', '18:00'),
('miercoles-18:00', '09626574Q', 'MMA', 'miercoles', '18:00', '20:00'),
('jueves-10:00', '50489319H', 'Boxeo', 'jueves', '10:00', '12:00'),
('jueves-12:00', '09626574Q', 'MMA', 'jueves', '12:00', '14:00'),
('jueves-16:00', '55462206Y', 'Boxeo', 'jueves', '16:00', '18:00'),
('jueves-18:00', '09626574Q', 'MMA', 'jueves', '18:00', '20:00'),
('viernes-10:00', '09626574Q', 'Taekwondo', 'viernes', '10:00', '12:00'),
('viernes-12:00', '09626574Q', 'Karate', 'viernes', '12:00', '14:00'),
('viernes-16:00', '09626574Q', 'Taekwondo', 'viernes', '16:00', '18:00'),
('viernes-18:00', '09626574Q', 'Karate', 'viernes', '18:00', '20:00'),
('sabado-10:00', '50489319H', 'Boxeo', 'sabado', '10:00', '12:00'),
('sabado-12:00', '50489319H', 'MMA', 'sabado', '12:00', '14:00'),
('sabado-16:00', '55462206Y', 'Boxeo', 'sabado', '16:00', '18:00'),
('sabado-18:00', '09626574Q', 'MMA', 'sabado', '18:00', '20:00');





-- Insertar socios
INSERT INTO SOCIOS (dni, nombre, apellidos, fecha_nac, telefono, email, tarifa, cuota_mensual, cuenta_bancaria, fecha_alta) VALUES
('82709958A', 'Luis', 'Pérez Sánchez', '1988-10-12', '611987654', 'luis.perez@gym.com', 2, 25.00, 'ES9121000418450200051332', '2024-12-10'),
('93330782W', 'Luis', 'López Jiménez', '1995-01-25', '612345987', 'sofia.lopez@gym.com', 3, 30.00, NULL, '2024-12-10'),
('55534150Y', 'Javier', 'Rodríguez Fernández', '1990-07-04', '613456123', 'javier.rod@gym.com', 1, 20.00, 'ES1421000418450200051415', '2024-12-10'),
('28539505R', 'Laura', 'Hernández Ruiz', '1986-11-30', '614567890', 'laura.hernandez@gym.com', 2, 25.00, NULL, '2024-12-10'),
('84264364T', 'Ana', 'Martín García', '1992-04-20', '612345678', 'ana.martin@gym.com', 1, 20.00, NULL, '2024-12-10'),
('64592462K', 'Carlos', 'Fernández López', '1985-08-15', '613456789', 'carlos.fernandez@gym.com', 2, 25.00, 'ES9821000418450200051234', '2024-12-10'),
('04173382D', 'Sofía', 'García Pérez', '1990-03-10', '614567890', 'sofia.garcia@gym.com', 3, 30.00, NULL, '2024-12-10'),
('37945157X', 'Diego', 'Jiménez Ruiz', '1988-12-01', '615678901', 'diego.jimenez@gym.com', 1, 20.00, 'ES9121000418450200059876', '2024-12-10'),
('19517067A', 'Marta', 'Sánchez Martín', '1995-07-25', '616789012', 'marta.sanchez@gym.com', 2, 25.00, NULL, '2024-12-10'),
('78419440C', 'Jorge', 'Rodríguez Gómez', '1983-05-30', '617890123', 'jorge.rodriguez@gym.com', 3, 30.00, 'ES4721000418450200054567', '2024-12-10'),
('27988689N', 'Elena', 'Hernández López', '1991-11-15', '618901234', 'elena.hernandez@gym.com', 1, 20.00, NULL, '2024-12-10'),
('98222874V', 'Pablo', 'Gómez Ruiz', '1987-09-05', '619012345', 'pablo.gomez@gym.com', 2, 25.00, 'ES1421000418450200051234', '2024-12-10'),
('16416926D', 'Raúl', 'Martínez Sánchez', '1994-06-20', '620123456', 'raul.martinez@gym.com', 3, 30.00, NULL, '2024-12-10'),
('94192599D', 'Clara', 'Vázquez Fernández', '1996-02-14', '621234567', 'clara.vazquez@gym.com', 1, 20.00, 'ES9121000418450200057890', '2024-12-10'),
('17304391L', 'Miguel', 'Gómez Martín', '1993-06-15', '615678234', 'miguel.gomez@gym.com', 3, 30.00, 'ES4721000418450200051516', '2024-12-10');


-- Insertar inscripciones en la tabla intermedia SOCIOS_CLASES
INSERT INTO SOCIOS_CLASES (dni_socio, id_clase) VALUES
-- Socio 82709958A (tarifa 2) - Máximo 2 clases
('82709958A', 'lunes-12:00'),
('82709958A', 'viernes-10:00'),

-- Socio 93330782W (tarifa 3) - Máximo 3 clases
('93330782W', 'lunes-12:00'),
('93330782W', 'viernes-10:00'),
('93330782W', 'miercoles-10:00'),

-- Socio 04173382D (tarifa 3) - Máximo 3 clases
('04173382D', 'lunes-12:00'),
('04173382D', 'miercoles-18:00'),
('04173382D', 'miercoles-10:00'),

-- Socio 28539505R (tarifa 2) - Máximo 2 clases
('28539505R', 'lunes-18:00'),
('28539505R', 'viernes-12:00'),

-- Socio 78419440C (tarifa 3) - Máximo 3 clases
('78419440C', 'lunes-18:00'),
('78419440C', 'viernes-12:00'),
('78419440C', 'jueves-18:00'),

-- Socio 16416926D (tarifa 3) - Máximo 3 clases
('16416926D', 'lunes-18:00'),
('16416926D', 'miercoles-16:00'),
('16416926D', 'sabado-18:00'),

-- Socio 64592462K (tarifa 2) - Máximo 2 clases
('64592462K', 'miercoles-18:00'),
('64592462K', 'miercoles-12:00'),

-- Socio 17304391L (tarifa 3) - Máximo 3 clases
('17304391L', 'miercoles-12:00'),
('17304391L', 'miercoles-18:00'),
('17304391L', 'jueves-18:00'),

-- Socio 19517067A (tarifa 2) - Máximo 2 clases
('19517067A', 'miercoles-18:00'),
('19517067A', 'jueves-16:00'),

-- Socio 55534150Y (tarifa 1) - Máximo 1 clase
('55534150Y', 'viernes-10:00'),

-- Socio 84264364T (tarifa 1) - Máximo 1 clase
('84264364T', 'viernes-12:00'),

-- Socio 27988689N (tarifa 1) - Máximo 1 clase
('27988689N', 'jueves-10:00'),

-- Socio 98222874V (tarifa 2) - Máximo 2 clases
('98222874V', 'jueves-12:00'),
('98222874V', 'sabado-12:00'),

-- Socio 94192599D (tarifa 1) - Máximo 1 clase
('94192599D', 'jueves-16:00'),

-- Socio 37945157X (tarifa 1) - Máximo 1 clase
('37945157X', 'sabado-10:00');


-- Insertar datos en Recepcionistas
INSERT INTO RECEPCIONISTAS VALUES 
('16280029P', 'Iago', 'Fernandez Pereira', '1988-08-23', '677123456', 'Iago@gmail.com', 'ES9345678921244368890123', 'recepcionista', 1150, 40, '$2y$10$GaYjfJDhGQFTGByJIjE2u.V1qb/HrYgVD34xh.oixO7GzxFamchwK');
