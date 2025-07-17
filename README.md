# Basic E-commerce CRUD API Project (Raw PHP)

## Project Purpose

This project was built to understand:

* The process of creating APIs using raw PHP.
* Fundamental Object-Oriented Programming (OOP) principles in real development.
* Basic software development practices.
* Version control using Git and GitHub.
* JWT-based authentication and authorization.
* Basic database design and API development workflow.

## Modules

The project contains three main modules:

* **Users Module** (Admin and Customer roles)
* **Products Module**
* **Orders Module**

Each module supports basic CRUD functionalities. Role-Based Access Control (RBAC) has been implemented to restrict access based on user roles. Proper validation is applied in API endpoints.

---

## Key Features

* JWT-based authentication (using Firebase JWT library).
* Role-Based Access Control (RBAC).
* CRUD operations for Users, Products, and Orders.
* Dependency Injection (Partially Implemented).
* Composer Autoloading.
* Database operations using PDO with PostgreSQL.
* Laravel Herd with NGINX as the development server.
* Database management via TablePlus.
* Postman for API testing.
* Clean separation of Controllers, Models, and Helpers.
* Basic database schema design included.
* Used VS Code as IDE

---

## API Endpoints

### Products Module

```
POST   /products            // Create Product
GET    /products            // List All Products
GET    /products/{id}       // Get Product by ID
PATCH  /products/{id}       // Update Product
DELETE /products/{id}       // Delete Product
```

### Users Module

```
POST   /users/register      // User Registration
POST   /users/login         // User Login
```

### Orders Module

```
POST   /orders              // Create Order
GET    /orders              // List All Orders
GET    /orders/{id}         // Get Order by ID
GET    /my-orders           // List Orders of Logged-In User
PATCH  /orders/{id}         // Update Order Quantity
DELETE /orders/{id}         // Delete Order
```

---

## Tools & Technologies

* Raw PHP
* PDO with PostgreSQL
* Composer (with Autoloading)
* Firebase JWT Library
* Laravel Herd (NGINX)
* TablePlus (Database Management)
* Postman (API Testing)
* Git & GitHub

---

## Challenges Faced

1. Lack of frequent and meaningful commits.
2. Unnecessary and sometimes meaningless comments in code.
3. Poor time distribution during development.
4. Areas of improvement in OOP usage (traits, interfaces, better dependency injection).
5. Found resolving merge conflicts to be intimidating

---

## Future Improvements

* Implement Traits and Interfaces for better code organization.
* Refactor to follow SOLID principles more strictly.
* Improve error handling and validation further.
* Remove unnecessary comments and improve code readability.
* Implement proper logout mechanism using JWT invalidation strategies.
* Add Unit Testing.

---

## Conclusion

This project provided hands-on experience in backend development using raw PHP, focusing on understanding API architecture, authentication mechanisms, role management, and applying basic development standards in a structured way.
