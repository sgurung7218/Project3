-- Customer Table
CREATE TABLE Customer (
    CustomerID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    Age INT NOT NULL,
    Address VARCHAR(200) NOT NULL
);

-- Suppliers Table
CREATE TABLE Suppliers (
    supplier_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    address VARCHAR(255)
);

-- Bank Table
CREATE TABLE Bank_Accounts (
    account_id INT AUTO_INCREMENT PRIMARY KEY,
    account_number VARCHAR(50) NOT NULL UNIQUE,
    account_type ENUM('savings', 'checking', 'business', 'loan') NOT NULL,
    balance DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    currency VARCHAR(10) DEFAULT 'USD',
    opening_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    account_status ENUM('active', 'inactive', 'suspended', 'closed') DEFAULT 'active'
);

-- Orders Table
CREATE TABLE Orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES Customer(CustomerID)
);

-- Restock Table
CREATE TABLE Restock (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    request_date DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Bank Records Table
CREATE TABLE Bank_records (
    transaction_id INT AUTO_INCREMENT PRIMARY KEY,
    account_id INT NOT NULL,
    order_id INT,
    request_id INT,
    transaction_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    amount DECIMAL(15, 2) NOT NULL,
    payment_method ENUM('credit_card', 'debit_card', 'bank_transfer', 'cash', 'paypal') NOT NULL,
    transaction_status ENUM('completed', 'pending', 'failed') DEFAULT 'pending',
    FOREIGN KEY (account_id) REFERENCES Bank_Accounts(account_id),
    FOREIGN KEY (order_id) REFERENCES Orders(order_id),
    FOREIGN KEY (request_id) REFERENCES Restock(request_id)
);

-- Product Table
CREATE TABLE Product (
    Product_id INT AUTO_INCREMENT PRIMARY KEY,
    price DECIMAL(10,2) NOT NULL,
    Stock INT NOT NULL,
    Name VARCHAR(100),
    Description VARCHAR(300)
);

-- Contain Table
CREATE TABLE Contain (
    order_id INT,
    product_id INT,
    Quantity INT NOT NULL,
    PRIMARY KEY (order_id, product_id),
    FOREIGN KEY (order_id) REFERENCES Orders(order_id),
    FOREIGN KEY (product_id) REFERENCES Product(Product_id)
);


-- RestockRequest Table
CREATE TABLE RestockRequest (
    RequestID INT AUTO_INCREMENT PRIMARY KEY,
    ProductID INT,
    SupplierID INT,
    FOREIGN KEY (ProductID) REFERENCES Product(Product_id),
    FOREIGN KEY (SupplierID) REFERENCES Suppliers(supplier_id)
);

-- Customers
INSERT INTO Customer (Name, Age, Address) VALUES
('Alice Smith', 30, '123 Maple St'),
('Bob Johnson', 45, '456 Oak St'),
('Carol Davis', 28, '789 Pine St'),
('David Lee', 36, '321 Birch St'),
('Eva Martinez', 50, '654 Cedar St');

-- Products
INSERT INTO Product (price, Stock, Name, Description) VALUES
(10.99, 100, 'Pen', 'Blue ink ballpoint pen'),
(5.49, 200, 'Notebook', 'A5 ruled notebook'),
(3.99, 150, 'Eraser', 'Soft rubber eraser'),
(2.50, 300, 'Pencil', 'HB graphite pencil'),
(12.75, 80, 'Stapler', 'Standard office stapler'),
(7.60, 120, 'Markers', 'Pack of 4 colored markers'),
(14.00, 60, 'Scissors', 'Office scissors 7-inch'),
(9.99, 90, 'Tape', 'Transparent adhesive tape'),
(6.89, 110, 'Glue Stick', 'Solid glue stick 20g'),
(15.25, 70, 'Ruler', 'Metal ruler 30cm');

-- Suppliers
INSERT INTO Suppliers (name, email, phone, address) VALUES
('Global Stationery Co.', 'info@globalstat.com', '555-1111', '111 Supply Rd'),
('OfficeGoods Inc.', 'contact@officegoods.com', '555-2222', '222 Warehouse Ln'),
('PaperMart', 'support@papermart.com', '555-3333', '333 Depot Ave');

-- Orders
INSERT INTO Orders (customer_id) VALUES
(1), (1), (2), (2), (3), (3), (4), (4), (5), (5);

-- Contain
INSERT INTO Contain (order_id, product_id, Quantity) VALUES
(1, 1, 2), (1, 2, 1),
(2, 3, 5), (2, 4, 2),
(3, 5, 1), (3, 6, 1), (3, 7, 1),
(4, 8, 4), (4, 9, 2),
(5, 10, 3), (5, 1, 1),
(6, 2, 2), (6, 3, 2),
(7, 4, 1), (7, 5, 1),
(8, 6, 3), (8, 7, 2),
(9, 8, 2), (9, 9, 1),
(10, 10, 1), (10, 1, 2);

-- Bank Accounts
INSERT INTO Bank_Accounts (account_number, account_type, balance) VALUES
('CUST001', 'checking', 1000.00),  -- Alice
('CUST002', 'checking', 800.00),   -- Bob
('CUST003', 'checking', 1200.00),  -- Carol
('CUST004', 'checking', 950.00),   -- David
('CUST005', 'checking', 1100.00),  -- Eva
('STORE001', 'business', 5000.00); -- Store Owner
