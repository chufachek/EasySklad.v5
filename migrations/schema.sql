CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100) NULL,
    nickname VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    email_verified TINYINT(1) DEFAULT 0,
    email_verify_token VARCHAR(64) NULL,
    twofa_enabled TINYINT(1) DEFAULT 0,
    created_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip VARCHAR(45) NOT NULL,
    identifier VARCHAR(150) NOT NULL,
    attempt_type VARCHAR(20) NOT NULL,
    attempts_count INT DEFAULT 0,
    locked_until DATETIME NULL,
    updated_at DATETIME NOT NULL,
    INDEX idx_login_attempts (ip, identifier, attempt_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE email_twofa_codes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    code_hash VARCHAR(255) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at DATETIME NOT NULL,
    used TINYINT(1) DEFAULT 0,
    INDEX idx_twofa_user (user_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    owner_user_id INT NOT NULL,
    name VARCHAR(200) NOT NULL,
    inn VARCHAR(20) NULL,
    business_type VARCHAR(100) NOT NULL,
    currency VARCHAR(10) DEFAULT 'RUB',
    tax_mode VARCHAR(20) DEFAULT 'no_vat',
    vat_default_rate VARCHAR(10) DEFAULT 'none',
    vat_price_mode VARCHAR(20) DEFAULT 'included',
    created_at DATETIME NOT NULL,
    INDEX idx_company_owner (owner_user_id),
    FOREIGN KEY (owner_user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE company_users (
    company_id INT NOT NULL,
    user_id INT NOT NULL,
    role VARCHAR(20) NOT NULL,
    PRIMARY KEY (company_id, user_id),
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE warehouses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    address VARCHAR(255) NULL,
    created_at DATETIME NOT NULL,
    INDEX idx_warehouse_company (company_id),
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE product_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    name VARCHAR(120) NOT NULL,
    created_at DATETIME NOT NULL,
    INDEX idx_category_company (company_id),
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    category_id INT NULL,
    name VARCHAR(200) NOT NULL,
    sku VARCHAR(100) NULL,
    barcode VARCHAR(120) NULL,
    unit VARCHAR(20) DEFAULT 'шт',
    cost_price DECIMAL(12,2) DEFAULT 0,
    sell_price DECIMAL(12,2) DEFAULT 0,
    vat_rate VARCHAR(10) DEFAULT 'none',
    created_at DATETIME NOT NULL,
    INDEX idx_product_company (company_id),
    INDEX idx_product_category (category_id),
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES product_categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE stock (
    company_id INT NOT NULL,
    warehouse_id INT NOT NULL,
    product_id INT NOT NULL,
    qty DECIMAL(12,3) DEFAULT 0,
    reserved_qty DECIMAL(12,3) DEFAULT 0,
    PRIMARY KEY (company_id, warehouse_id, product_id),
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
    FOREIGN KEY (warehouse_id) REFERENCES warehouses(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE counterparties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    type VARCHAR(20) NOT NULL,
    name VARCHAR(200) NOT NULL,
    phone VARCHAR(50) NULL,
    email VARCHAR(150) NULL,
    created_at DATETIME NOT NULL,
    INDEX idx_counterparty_company (company_id),
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    warehouse_id INT NOT NULL,
    type VARCHAR(20) NOT NULL,
    status VARCHAR(20) NOT NULL,
    counterparty_id INT NULL,
    payment_type VARCHAR(20) NULL,
    totals_net DECIMAL(12,2) DEFAULT 0,
    totals_vat DECIMAL(12,2) DEFAULT 0,
    totals_gross DECIMAL(12,2) DEFAULT 0,
    created_by INT NOT NULL,
    created_at DATETIME NOT NULL,
    INDEX idx_documents_company (company_id),
    INDEX idx_documents_warehouse (warehouse_id),
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
    FOREIGN KEY (warehouse_id) REFERENCES warehouses(id) ON DELETE CASCADE,
    FOREIGN KEY (counterparty_id) REFERENCES counterparties(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE document_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    document_id INT NOT NULL,
    product_id INT NOT NULL,
    qty DECIMAL(12,3) NOT NULL,
    price DECIMAL(12,2) NOT NULL,
    vat_rate VARCHAR(10) DEFAULT 'none',
    line_net DECIMAL(12,2) DEFAULT 0,
    line_vat DECIMAL(12,2) DEFAULT 0,
    line_gross DECIMAL(12,2) DEFAULT 0,
    INDEX idx_doc_items_doc (document_id),
    FOREIGN KEY (document_id) REFERENCES documents(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
