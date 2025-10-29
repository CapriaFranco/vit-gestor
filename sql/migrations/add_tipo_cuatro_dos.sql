-- Migration to add tipo_cuatro_dos field to existing equipos table
-- This allows adding the field without deleting existing data

USE vit_gestor_db;

-- Add the new column after sistema_juego
ALTER TABLE equipos 
ADD COLUMN tipo_cuatro_dos ENUM('c', 'o') DEFAULT NULL 
AFTER sistema_juego;

-- Update existing 4:2 teams to use 'c' (centrales) as default
UPDATE equipos 
SET tipo_cuatro_dos = 'c' 
WHERE sistema_juego = '4:2' AND tipo_cuatro_dos IS NULL;
