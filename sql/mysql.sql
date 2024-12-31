-- Create database
CREATE DATABASE IF NOT EXISTS db_s2_ETU003286;

USE db_s2_ETU003286;

-- ------------------------------
-- User Table (christmas_user)
-- ------------------------------
CREATE TABLE christmas_user (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------
-- Category Table (christmas_category)
-- ------------------------------
CREATE TABLE christmas_category (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL
);

-- ------------------------------
-- Gift Table (christmas_gift)
-- ------------------------------
CREATE TABLE christmas_gift (
    gift_id INT AUTO_INCREMENT PRIMARY KEY,
    gift_name VARCHAR(255) NOT NULL,
    category_id INT,
    price DECIMAL(10, 2) NOT NULL,
    description TEXT,
    stock_quantity INT DEFAULT 0,
    FOREIGN KEY (category_id) REFERENCES christmas_category(category_id)
);

-- ------------------------------
-- Move Table (christmas_move) - Only money-related movements (deposits and withdrawals)
-- ------------------------------
CREATE TABLE christmas_move (
    move_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    amount DECIMAL(10, 2) NOT NULL,  -- Positive for deposits, negative for withdrawals
    description VARCHAR(255),
    move_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES christmas_user(user_id)
);

-- ------------------------------
-- Gift Transaction Table (christmas_gift_transaction) - For gift selection
-- ------------------------------
CREATE TABLE christmas_gift_transaction (
    transaction_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    gift_id INT,
    quantity INT NOT NULL,
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES christmas_user(user_id),
    FOREIGN KEY (gift_id) REFERENCES christmas_gift(gift_id)
);

-- ------------------------------
-- View to Calculate User Balance (christmas_user_balance_view)
-- ------------------------------
CREATE VIEW christmas_user_balance_view AS
SELECT
    u.user_id,
    u.username,
    COALESCE(SUM(m.amount), 0) AS current_balance
FROM
    christmas_user u
LEFT JOIN
    christmas_move m ON u.user_id = m.user_id
GROUP BY
    u.user_id;

-- ------------------------------
-- View for Deposits (christmas_user_deposits_view)
-- ------------------------------
CREATE VIEW christmas_user_deposits_view AS
SELECT
    u.user_id,
    u.username,
    m.move_id,
    m.amount AS deposit_amount,
    m.description,
    m.move_date
FROM
    christmas_user u
JOIN
    christmas_move m ON u.user_id = m.user_id
WHERE
    m.amount > 0;

-- ------------------------------
-- View for Withdrawals (christmas_user_withdrawals_view)
-- ------------------------------
CREATE VIEW christmas_user_withdrawals_view AS
SELECT
    u.user_id,
    u.username,
    m.move_id,
    m.amount AS withdrawal_amount,
    m.description,
    m.move_date
FROM
    christmas_user u
JOIN
    christmas_move m ON u.user_id = m.user_id
WHERE
    m.amount < 0;
