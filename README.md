# ParcelBuddy Delivery Management and Tracking Website

ParcelBuddy is an academic delivery management and tracking website developed as part of my Computer Science studies at the University of Salford between October 2023 and December 2023.

The project was designed to support basic parcel delivery operations, including secure user login, delivery status tracking, administrator management tools and delivery staff management. It was built using Object-Oriented PHP, an MVC-style structure, MySQL, HTML and CSS.

> **Project status:** This is an older university coursework project and is no longer actively maintained. The original live version was hosted on the University of Salford Poseidon hosting environment, but that link is no longer active. This repository is kept as evidence of my early backend development, database design and PHP MVC experience.

---

## Project Overview

ParcelBuddy was created to demonstrate how a delivery company could manage parcel information, staff records and delivery progress through a web-based system.

The main aim was to build a structured PHP application that allowed different users to interact with delivery data securely. Administrators were able to manage key records through CRUD functionality, while users could log in and track delivery information.

This project helped me develop my understanding of backend development, relational databases, authentication, MVC architecture and responsive web design.

---

## Key Features

* Secure user login system
* Password-protected access to the system
* Delivery status tracking
* Administrator dashboard for managing delivery records
* CRUD functionality for creating, reading, updating and deleting delivery-related data
* Delivery staff management
* MySQL database integration
* Responsive HTML/CSS interface
* Object-Oriented PHP structure
* MVC-style project organisation

---

## Technologies Used

* PHP
* Object-Oriented Programming
* MVC Architecture
* MySQL
* HTML
* CSS
* University Poseidon Hosting Environment

---

## What I Worked On

During this project, I contributed to the development of a delivery management website that allowed users to log in, track delivery statuses and manage delivery staff information.

A key part of the project was implementing CRUD functionality for administrators. This allowed delivery records and staff-related information to be created, viewed, updated and deleted through the system.

I also worked with MySQL to support user authentication, delivery data storage and database-driven page content. This helped me understand how backend logic, database design and user-facing pages connect together in a full web application.

---

## Database and Authentication

The system used a MySQL database to store user, delivery and staff-related information.

Authentication was implemented through a login system connected to the database. Password handling was designed with security in mind, including password encryption/hashing to avoid storing plain-text passwords.

The project also helped me understand the importance of database schema design, query structure and access control in web applications.

---

## Performance Considerations

As part of the development process, database performance and page loading were considered. This included looking at areas such as:

* Database indexing
* Query optimisation
* Reducing unnecessary database calls
* Improving the structure of database-driven pages

These improvements helped strengthen my understanding of how database design can affect the usability and performance of a web application.

---

## Skills Developed

This project helped me build practical experience in:

* Backend web development with PHP
* Object-Oriented Programming
* MVC-style application structure
* MySQL database design
* Secure login and authentication systems
* CRUD application development
* Responsive web interface design
* Structuring a multi-page web application
* Debugging and improving database-driven functionality

---

## Screenshots

 /docs/screenshots/admin-dashboard.jpeg

---

## Setup Notes

This project was originally developed for a university hosting environment, so local setup may require configuration changes.

A typical local setup would require:

1. A local PHP server environment such as XAMPP, WAMP or MAMP
2. MySQL database setup
3. Importing the project database file, if available
4. Updating database connection settings
5. Running the project through a local server

Example database configuration values may need to be updated depending on the local environment.

```php
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "parcelbuddy";
```

---

## Reflection

Although this was an earlier academic project, it was important in developing my understanding of backend web development. It gave me practical experience with PHP, MySQL, authentication, CRUD functionality and MVC-style application structure.

The project also helped me move beyond simple static websites and understand how database-driven systems are designed, structured and maintained.

If I were to rebuild this project today, I would improve it by using a modern framework, adding clearer role-based access control, improving validation, strengthening security testing and deploying it through a more stable hosting platform.
