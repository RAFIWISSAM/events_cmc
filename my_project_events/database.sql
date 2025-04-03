-- Database structure
CREATE DATABASE cmc_event_hub;
USE cmc_event_hub;

-- Events table
CREATE TABLE events (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  date DATE NOT NULL,
  location VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  price VARCHAR(100) NOT NULL,
  image VARCHAR(255) NOT NULL,
  category VARCHAR(100) NOT NULL,
  invited_count INT DEFAULT 0,
  attendee_count INT DEFAULT 0
);

-- Participants table
CREATE TABLE participants (
  id INT AUTO_INCREMENT PRIMARY KEY,
  event_id INT NOT NULL,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  email VARCHAR(255) NOT NULL,
  registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

-- Users table
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  is_admin TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Locations table
CREATE TABLE locations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL
);

-- Event categories table
CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL
);

-- Insert sample events
INSERT INTO events (title, date, location, price, description, image, category, invited_count, attendee_count) VALUES 
('Forum des Métiers du Numérique', '2023-12-15', 'Amphithéâtre Central', 'Gratuit', 'Découvrez les opportunités dans le secteur du numérique avec nos partenaires industriels. Au programme : conférences, ateliers pratiques et rencontres avec des professionnels du secteur.', 'img/events/event1.jpg', 'Technologie', 100, 75),
('Workshop Développement Web', '2023-12-20', 'Salle 102', '50 MAD', 'Atelier pratique sur les dernières technologies de développement web. Venez avec votre ordinateur pour participer à des exercices pratiques guidés par des experts du domaine.', 'img/events/OIP.jpg', 'Formation', 30, 25),
('Conférence Intelligence Artificielle', '2024-01-10', 'Square Innovation', 'Gratuit pour les étudiants', 'Une journée dédiée à l''intelligence artificielle et ses applications dans différents secteurs d''activité. Des intervenants de renommée internationale partageront leurs connaissances.', 'img/events/R.png', 'Technologie', 200, 180),
('Hackathon CMC 2024', '2024-01-25', 'Espace Collaboration', '100 MAD par équipe', '48 heures de programmation intense pour résoudre des défis réels proposés par nos partenaires. Formez votre équipe et tentez de remporter des prix exceptionnels!', 'img/events/event4.jpg', 'Compétition', 150, 120),
('Séminaire Cybersécurité', '2024-02-05', 'Salle de Conférence 3', '200 MAD', 'Protégez votre entreprise contre les cybermenaces. Ce séminaire abordera les meilleures pratiques de sécurité informatique et les stratégies de défense contre les attaques.', 'img/events/event5.jpg', 'Sécurité', 75, 60),
('Journée Portes Ouvertes', '2024-02-20', 'Campus CMC', 'Entrée libre', 'Venez découvrir notre campus, nos formations et rencontrer nos équipes pédagogiques. Des démonstrations de projets étudiants seront présentées tout au long de la journée.', 'img/events/event6.jpg', 'Orientation', 500, 350);

-- Insert sample locations
INSERT INTO locations (name) VALUES 
('Amphithéâtre Central'),
('Salle 102'),
('Square Innovation'),
('Espace Collaboration'),
('Salle de Conférence 3'),
('Campus CMC'),
('Bibliothèque Centrale'),
('Laboratoire Informatique');

-- Insert sample categories
INSERT INTO categories (name) VALUES 
('Technologie'),
('Formation'),
('Compétition'),
('Sécurité'),
('Orientation'),
('Networking'),
('Workshop'),
('Conférence');

-- Insert admin user (password: admin123)
INSERT INTO users (name, email, password, is_admin) VALUES 
('Administrateur', 'admin@gmail.com', '$2a$12$3BDTWsFeQc7HN5AgQeeNmOdlvsgVBSu4FbjyenAap3dKIlimthcr2', 1);
