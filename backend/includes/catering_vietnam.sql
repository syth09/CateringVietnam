DROP SCHEMA IF EXISTS catering_vietnam;
CREATE DATABASE catering_vietnam;
USE catering_vietnam;

-- Bảng users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50),
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    address VARCHAR(250),
    phone INT,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bảng menu
CREATE TABLE menu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dish_name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bảng orders
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Bảng order_items
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    menu_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (menu_id) REFERENCES menu(id)
);

-- Bảng bookings
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    booking_date DATETIME NOT NULL,
    number_of_people INT NOT NULL,
    special_requests TEXT,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Bảng payments
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    payment_method ENUM('credit_card', 'paypal', 'vnpay') NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    transaction_id VARCHAR(255),
    status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);

-- Insert dữ liệu mẫu vào bảng users
INSERT INTO users (name, username, password, email, role) VALUES
('Admin', 'admin', '123456a@', 'admin@cateringvn.com', 'admin'); -- Password: password
INSERT INTO users (name, username, password, email, role) VALUES
('User 2', 'user2', 'password2', 'user2@example.com', 'user'),
('User 3', 'user3', 'password3', 'user3@example.com', 'user');

-- Insert dữ liệu mẫu vào bảng menu
INSERT INTO menu (dish_name, description, price, image) VALUES
('Pho Bo', 'Traditional Vietnamese beef noodle soup', 10.00, 'images/food/pho_bo.jpg'),
('Bun Cha', 'Grilled pork with rice noodles', 8.50, 'images/food/bun_cha.jpg'),
('Banh Mi', 'Vietnamese baguette sandwich', 5.00, 'images/food/banh_mi.jpg'),
('Goi Cuon', 'Fresh spring rolls with shrimp and herbs', 6.00, 'images/food/goi_cuon.jpg'),
('Com Tam', 'Broken rice with grilled pork', 7.50, 'images/food/com_tam.jpg'),
('Banh Xeo', 'Vietnamese crispy pancake', 9.00, 'images/food/banh_xeo.jpg'),
('Cha Gio', 'Fried spring rolls', 4.50, 'images/food/cha_gio.jpg'),
('Mi Quang', 'Turmeric noodles with shrimp and pork', 8.00, 'images/food/mi_quang.png'),
('Banh Cuon', 'Steamed rice rolls with minced pork', 6.50, 'images/food/banh_cuon.jpg'),
('Ca Kho To', 'Caramelized fish in clay pot', 12.00, 'images/food/ca_kho_to.jpg'),
('Bo Luc Lac', 'Shaking beef with vegetables', 14.00, 'images/food/bo_luc_lac.jpg'),
('Canh Chua', 'Sour soup with fish and vegetables', 9.50, 'images/food/canh_chua.jpg'),
('Bun Bo Hue', 'Spicy beef noodle soup from Hue', 11.00, 'images/food/bun_bo_hue.jpg'),
('Banh Beo', 'Steamed rice cakes with shrimp', 5.50, 'images/food/banh_beo.jpg'),
('Banh Bot Loc', 'Clear shrimp and pork dumplings', 6.00, 'images/food/banh_bot_loc.jpg'),
('Che Ba Mau', 'Three-color dessert with beans and jelly', 4.00, 'images/food/che_ba_mau.jpg'),
('Banh Flan', 'Vietnamese caramel flan', 3.50, 'images/food/banh_flan.jpg'),
('Tra Da', 'Iced tea', 1.50, 'images/food/tra_da.jpg'),
('Ca Phe Sua Da', 'Vietnamese iced coffee with condensed milk', 3.00, 'images/food/ca_phe_sua_da.png'),
('Sinh To Bo', 'Avocado smoothie', 4.50, 'images/food/sinh_to_bo.jpg');

-- Insert dữ liệu mẫu vào bảng orders
INSERT INTO orders (user_id, total_amount, order_date, status) VALUES
(2, 25.50, '2023-10-01 12:00:00', 'completed'),
(3, 18.00, '2023-10-02 13:30:00', 'pending'),
(2, 32.00, '2023-10-03 14:45:00', 'completed'),
(3, 15.50, '2023-10-04 15:15:00', 'completed'),
(2, 22.00, '2023-10-05 16:30:00', 'pending'),
(3, 10.00, '2023-10-06 17:00:00', 'completed'),
(2, 28.50, '2023-10-07 18:15:00', 'completed'),
(3, 19.00, '2023-10-08 19:30:00', 'pending'),
(2, 14.00, '2023-10-09 20:00:00', 'completed'),
(3, 21.50, '2023-10-10 21:15:00', 'pending');

-- Insert dữ liệu mẫu vào bảng order_items
INSERT INTO order_items (order_id, menu_id, quantity, price) VALUES
(1, 1, 2, 10.00), -- Order 1: 2x Pho Bo
(1, 3, 1, 5.00),  -- Order 1: 1x Banh Mi
(1, 5, 1, 7.50),  -- Order 1: 1x Com Tam

(2, 2, 1, 8.50),  -- Order 2: 1x Bun Cha
(2, 4, 2, 6.00),  -- Order 2: 2x Goi Cuon

(3, 6, 1, 9.00),  -- Order 3: 1x Banh Xeo
(3, 7, 3, 4.50),  -- Order 3: 3x Cha Gio
(3, 8, 1, 8.00),  -- Order 3: 1x Mi Quang

(4, 9, 2, 6.50),  -- Order 4: 2x Banh Cuon
(4, 10, 1, 12.00), -- Order 4: 1x Ca Kho To

(5, 11, 1, 14.00), -- Order 5: 1x Bo Luc Lac
(5, 12, 1, 9.50),  -- Order 5: 1x Canh Chua

(6, 13, 1, 11.00), -- Order 6: 1x Bun Bo Hue
(6, 14, 2, 5.50),  -- Order 6: 2x Banh Beo

(7, 15, 1, 6.00),  -- Order 7: 1x Banh Bot Loc
(7, 16, 1, 4.00),  -- Order 7: 1x Che Ba Mau
(7, 17, 1, 3.50),  -- Order 7: 1x Banh Flan

(8, 18, 2, 1.50),  -- Order 8: 2x Tra Da
(8, 19, 1, 3.00),  -- Order 8: 1x Ca Phe Sua Da

(9, 20, 1, 4.50),  -- Order 9: 1x Sinh To Bo
(9, 1, 1, 10.00),  -- Order 9: 1x Pho Bo

(10, 2, 1, 8.50),  -- Order 10: 1x Bun Cha
(10, 3, 1, 5.00),  -- Order 10: 1x Banh Mi
(10, 4, 1, 6.00);  -- Order 10: 1x Goi Cuon

-- Thêm 2 đồ ăn
INSERT INTO menu (dish_name, description, price, image) VALUES
('Banh Khot', 'Mini savory pancakes with shrimp', 7.00, 'images/food/banh_khot.jpg'),
('Bun Rieu', 'Crab noodle soup with tomato broth', 9.50, 'images/food/bun_rieu.jpg');

-- Thêm 2 món tráng miệng
INSERT INTO menu (dish_name, description, price, image) VALUES
('Che Chuoi', 'Sweet banana coconut dessert', 4.50, 'images/food/che_chuoi.jpg'),
('Banh Pia', 'Vietnamese flaky pastry with mung bean filling', 5.00, 'images/food/banh_pia.jpg');

-- Thêm 2 thức uống
INSERT INTO menu (dish_name, description, price, image) VALUES
('Nuoc Cam', 'Fresh orange juice', 3.50, 'images/food/nuoc_cam.jpg'),
('Sinh To Dau', 'Strawberry smoothie', 4.50, 'images/food/sinh_to_dau.jpg');

INSERT INTO payments (order_id, payment_method, amount, transaction_id, status, payment_date, created_at, updated_at) VALUES
(1, 'credit_card', 25.50, 'TXN123456789', 'completed', '2023-10-01 12:10:00', '2023-10-01 12:00:00', '2023-10-01 12:10:00'),
(2, 'paypal', 18.00, 'TXN987654321', 'pending', '2023-10-02 14:00:00', '2023-10-02 13:30:00', '2023-10-02 14:00:00'),
(3, 'vnpay', 32.00, 'TXN111223344', 'completed', '2023-10-03 15:00:00', '2023-10-03 14:45:00', '2023-10-03 15:00:00'),
(4, 'credit_card', 15.50, 'TXN223344556', 'completed', '2023-10-04 16:00:00', '2023-10-04 15:15:00', '2023-10-04 16:00:00'),
(5, 'paypal', 22.00, 'TXN334455667', 'pending', '2023-10-05 17:30:00', '2023-10-05 16:30:00', '2023-10-05 17:30:00'),
(6, 'vnpay', 10.00, 'TXN445566778', 'completed', '2023-10-06 18:30:00', '2023-10-06 17:00:00', '2023-10-06 18:30:00'),
(7, 'credit_card', 28.50, 'TXN556677889', 'completed', '2023-10-07 19:00:00', '2023-10-07 18:15:00', '2023-10-07 19:00:00'),
(8, 'paypal', 19.00, 'TXN667788990', 'pending', '2023-10-08 20:00:00', '2023-10-08 19:30:00', '2023-10-08 20:00:00'),
(9, 'vnpay', 14.00, 'TXN778899001', 'completed', '2023-10-09 21:30:00', '2023-10-09 20:00:00', '2023-10-09 21:30:00'),
(10, 'credit_card', 21.50, 'TXN889900112', 'pending', '2023-10-10 22:00:00', '2023-10-10 21:15:00', '2023-10-10 22:00:00');

INSERT INTO bookings (user_id, booking_date, number_of_people, special_requests, status, created_at, updated_at) VALUES
(2, '2023-10-01 11:00:00', 4, 'Vegetarian option needed', 'confirmed', '2023-10-01 10:00:00', '2023-10-01 11:00:00'),
(3, '2023-10-02 14:30:00', 2, 'Birthday celebration', 'pending', '2023-10-02 13:00:00', '2023-10-02 14:30:00'),
(2, '2023-10-03 16:00:00', 6, 'Request for outdoor seating', 'confirmed', '2023-10-03 15:00:00', '2023-10-03 16:00:00'),
(3, '2023-10-04 17:30:00', 3, 'No spicy food', 'confirmed', '2023-10-04 16:45:00', '2023-10-04 17:30:00'),
(2, '2023-10-05 19:00:00', 5, 'Child-friendly seating', 'pending', '2023-10-05 18:30:00', '2023-10-05 19:00:00'),
(3, '2023-10-06 20:30:00', 2, 'Special vegan dishes', 'confirmed', '2023-10-06 19:00:00', '2023-10-06 20:30:00'),
(2, '2023-10-07 21:00:00', 4, 'Window table', 'confirmed', '2023-10-07 20:00:00', '2023-10-07 21:00:00'),
(3, '2023-10-08 22:00:00', 3, 'Quiet corner', 'pending', '2023-10-08 21:00:00', '2023-10-08 22:00:00'),
(2, '2023-10-09 11:00:00', 7, 'Birthday cake required', 'confirmed', '2023-10-09 10:15:00', '2023-10-09 11:00:00'),
(3, '2023-10-10 13:30:00', 2, 'Anniversary celebration', 'pending', '2023-10-10 12:00:00', '2023-10-10 13:30:00');
