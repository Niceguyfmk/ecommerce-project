E-Commerce application

First Admin has to login then they can create products. Once, Products are made Customers can login and access these products.

1. Setup Database Migrations

    Users Table (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )

    Products Table (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        Size  VARCHAR(50) NOT NULL,
        Color VARCHAR(50) NOT NULL,
        Brand VARCHAR(100) NOT NULL,
        Description TEXT NOT NULL,
        Image VARCHAR(255) NOT NULL
        created_at datetime default current_timestamp
    )

2. Resource Controller - UserController, ProductController

3. Model Classes - UserModel, ProductModel

4. Routes:


