# Student Management System using PHP and MySQL

## 🌟About the Project
A web-based Student Management System developed using PHP, MySQL, HTML, and CSS to manage student records.

## 🎯Features
- Add, view, edit, and delete student records
- Search students by name or email
- User registration and login
- Password hashing and verification
- Session management and authentication
- Logout functionality
- Database operations using PDO prepared statements

## 🛠️Technologies Used
- HTML
- CSS
- PHP
- MySQL
- PDO
- XAMPP
- Visual Studio Code

## 🛠️Requirements
- XAMPP
- Web browser
- Code editor

## ⚙️Installation and Setup
1. Copy the project folder into the XAMPP `htdocs` directory.
2. Start Apache and MySQL in XAMPP.
3. Create a MySQL database named `student_management`.
4. Create the required `students` and `users` tables.
5. Check the database settings in `db.php`.
6. Open `http://localhost/student_management/` in your browser.

## 🔒Security
The project uses prepared statements, password hashing, input validation, and session-based authentication. CSRF protection is implemented for the delete request and should be extended to other state-changing forms.

## 👤Author
Abarna M.

## 🎯Project Purpose
This project was developed to practise PHP programming, database connectivity, CRUD operations, and authentication.
