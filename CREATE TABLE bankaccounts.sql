CREATE TABLE bankaccounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    preferred_bank VARCHAR(255) NOT NULL,
    account_number VARCHAR(255) NOT NULL,
    bank_name VARCHAR(255) NOT NULL,
    company_id INT,
    role ENUM('manager', 'admin', 'employee') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE bank_accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    preferred_bank VARCHAR(255),
    account_number VARCHAR(255),
    bank_name VARCHAR(255),
    company_id VARCHAR(255),
    role VARCHAR(50),
    company_name VARCHAR(255),
    manager_name VARCHAR(255),
    employee_name VARCHAR(255),
    manager_id VARCHAR(255),
    admin_id VARCHAR(255),
    employee_id VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
