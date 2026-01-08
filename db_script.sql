-- Création de la base de données
CREATE DATABASE IF NOT EXISTS eyora_db 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE eyora_db;

-- Table des catégories de produits
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des produits (lunettes)
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT,
    name VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image_url VARCHAR(500),
    type ENUM('vue', 'soleil', 'mode') NOT NULL,
    frame_color VARCHAR(50),
    material VARCHAR(50),
    gender ENUM('homme', 'femme', 'unisexe') DEFAULT 'unisexe',
    stock_quantity INT DEFAULT 10,
    is_featured BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Table des contacts/messages
CREATE TABLE contacts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    is_read BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des utilisateurs (pour extension future)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(150) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    role ENUM('admin', 'customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertion des catégories
INSERT INTO categories (name, description) VALUES
('Lunettes de Vue', 'Correction visuelle avec style'),
('Lunettes de Soleil', 'Protection UV avec élégance'),
('Lunettes de Mode', 'Accessoires tendance');

-- Insertion des produits (20 produits variés)
INSERT INTO products (category_id, name, description, price, type, frame_color, material, gender, image_url, is_featured) VALUES
(1, 'Eyora Classic Vue', 'Lunettes de vue classiques en acétate avec verres anti-reflets. Parfaites pour un usage quotidien.', 149.99, 'vue', 'Noir', 'Acétate', 'unisexe', 'glasses1.jpg', true),
(1, 'Eyora Slim Tech', 'Monture fine en titane, légère et résistante. Verres progressifs disponibles.', 199.99, 'vue', 'Argent', 'Titane', 'unisexe', 'glasses2.jpg', true),
(1, 'Eyora Vintage Round', 'Style rétro avec verres ronds. Monture en acétate de haute qualité.', 129.99, 'vue', 'Tortue', 'Acétate', 'femme', 'glasses3.jpg', false),
(1, 'Eyora Executive', 'Design professionnel pour homme. Monture rectangulaire en métal.', 179.99, 'vue', 'Or rose', 'Métal', 'homme', 'glasses4.jpg', true),
(1, 'Eyora Minimalist', 'Lunettes ultra-légères avec charnières flex. Parfaites pour les sportifs.', 159.99, 'vue', 'Bleu foncé', 'Polycarbonate', 'unisexe', 'glasses5.jpg', false),
(2, 'Eyora Sun Premium', 'Lunettes de soleil polarisées avec protection UV400. Verres dégradés.', 89.99, 'soleil', 'Noir/Gris', 'Acétate', 'unisexe', 'sunglasses1.jpg', true),
(2, 'Eyora Aviator Gold', 'Style aviateur classique en or. Verres miroir bleus.', 79.99, 'soleil', 'Or', 'Métal', 'homme', 'sunglasses2.jpg', true),
(2, 'Eyora Cat Eye', 'Lunettes de soleil féminines style œil de chat. Verres roses.', 69.99, 'soleil', 'Rose poudré', 'Acétate', 'femme', 'sunglasses3.jpg', true),
(2, 'Eyora Sport Polarized', 'Lunettes de sport avec verres polarisés et monture flexible.', 119.99, 'soleil', 'Noir/Rouge', 'Polycarbonate', 'homme', 'sunglasses4.jpg', false),
(2, 'Eyora Oversized', 'Grandes lunettes de soleil à la mode. Protection maximale.', 99.99, 'soleil', 'Blanc', 'Acétate', 'femme', 'sunglasses5.jpg', true),
(3, 'Eyora Fashion Frame', 'Monture transparente très tendance. Style streetwear.', 59.99, 'mode', 'Transparent', 'Acétate', 'unisexe', 'fashion1.jpg', true),
(3, 'Eyora Geometric', 'Formes géométriques uniques. Pièce de collection.', 89.99, 'mode', 'Bleu électrique', 'Acétate', 'unisexe', 'fashion2.jpg', false),
(3, 'Eyora Crystal', 'Monture incrustée de cristaux Swarovski. Édition limitée.', 299.99, 'mode', 'Cristal', 'Acétate', 'femme', 'fashion3.jpg', true),
(3, 'Eyora Wood Style', 'Monture en bois naturel. Style écologique et unique.', 129.99, 'mode', 'Bois naturel', 'Bois', 'unisexe', 'fashion4.jpg', false),
(3, 'Eyora Futurist', 'Design futuriste avec verres teintés bleus. Édition spéciale.', 159.99, 'mode', 'Argent mat', 'Alliage', 'homme', 'fashion5.jpg', true),
(1, 'Eyora Kids Fun', 'Lunettes de vue pour enfants avec montures colorées et résistantes.', 89.99, 'vue', 'Multicolore', 'Silicone', 'unisexe', 'kids1.jpg', false),
(2, 'Eyora Kids Sun', 'Lunettes de soleil pour enfants avec protection UV complète.', 49.99, 'soleil', 'Bleu/Orange', 'Plastique', 'unisexe', 'kids2.jpg', false),
(1, 'Eyora Senior Comfort', 'Lunettes de vue avec monture légère et verres progressifs.', 169.99, 'vue', 'Gris argent', 'Titane', 'unisexe', 'senior1.jpg', false),
(3, 'Eyora Limited Edition', 'Édition limitée en collaboration avec un artiste contemporain.', 399.99, 'mode', 'Noir/Or', 'Acétate/Métal', 'unisexe', 'limited1.jpg', true),
(2, 'Eyora Driving', 'Lunettes de conduite avec verres spéciaux pour réduire l éblouissement.', 139.99, 'soleil', 'Brun/Bronze', 'Acétate', 'homme', 'driving1.jpg', false);

-- INDEX pour optimiser les performances
CREATE INDEX idx_products_type ON products(type);
CREATE INDEX idx_products_featured ON products(is_featured);
CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_contacts_email ON contacts(email);
CREATE INDEX idx_contacts_created ON contacts(created_at);

-- COMPTE ADMIN PAR DÉFAUT (mot de passe: admin123)
INSERT INTO users (email, password_hash, first_name, last_name, role) VALUES
('admin@eyora.com', '$2y$10$YourHashedPasswordHere', 'Admin', 'Eyora', 'admin');


-- VUES pour rapports (optionnel)
CREATE VIEW v_products_details AS
SELECT 
    p.*,
    c.name as category_name,
    c.description as category_description
FROM products p
LEFT JOIN categories c ON p.category_id = c.id;

CREATE VIEW v_contact_messages AS
SELECT 
    id,
    name,
    email,
    subject,
    DATE(created_at) as message_date,
    is_read
FROM contacts
ORDER BY created_at DESC;

-- Message de confirmation
SELECT 'Base de données Eyora installée avec succès!' as message;