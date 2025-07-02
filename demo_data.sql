
-- Áreas
INSERT INTO Area (nombre_area) VALUES
('Sistemas'), ('Mantenimiento'), ('Recepción'), ('Cocina'), ('Ama de Llaves'), ('Ventas'), ('Banquetes');

-- Usuario administrador
INSERT INTO Usuario (nombre, correo, password, rol) VALUES
('Admin Principal', 'admin@demo.com', '$2y$10$qgknN2d0LeT2oKO3/2cZgO5RSYHtlhYOv0/YK/BwLrFqK80Hv2j3a', 'Administrador');
-- password: admin123

-- Jefe de área demo
INSERT INTO Usuario (nombre, correo, password, rol) VALUES
('Jefe Sistemas', 'jefe@demo.com', '$2y$10$3eqBhlq27ikZ2ZZsPC3L9O8JIXQ2NYrfSRFAM6iT60MWhGXrp4tly', 'JefeArea');
-- password: jefe123
