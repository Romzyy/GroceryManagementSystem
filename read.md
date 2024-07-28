\*\*\*\*\*\* Group Members: \*\*\*\*\*\*
Romit Patel
Jay Patel
Siddharth Shukla
Heet Shah.

\*\*\*\*\*\* Grocery Management System\_\*\*\*\*\*\*
This project is a Grocery Management System designed to manage sales, orders, and generate sales reports for a grocery store. It includes PHP scripts for backend logic and HTML for frontend display of data.

\*\*\*\*\* NEW UPDATE FOR THE APPLICATION \*\*\*\*\*\*

• Use PHP's password_hash() function for storing hashed passwords.
Implementing User Login
• Created an HTML form for user login.
• Implemented authentication logic to verify user credentials and start a new session
upon successful login.
• Redirected authenticated users to their profile page based on their role.
Managing Sessions and Cookies
• Start a session for authenticated users using PHP's session_start() function.
• Implemented a "Remember Me" feature using cookies, ensuring the secure storage of
the cookie data.
• Implemented logout functionality to destroy the session and clear cookies

Requirements
To run this application, ensure you have the following installed:

PHP
MySQL database
Installation

Database Setup:

Create a MySQL database named grocery_db.
user.sql file provided to create necessary table
Configuration:

Modify DataHandler.php to set your MySQL database credentials (username, password, host).

Usage
Access the Application:

Navigate to the URL where your application is hosted (e.g., http://localhost/grocery-management).

The sales_report.php page generates a comprehensive sales report including total orders, total sales, sales by item, and detailed order information.
Navigation:

Use links within the application (Back to Home) for navigation between different pages.
Structure
index.php: Entry point of the application.
sales_report.php: Generates and displays the sales report.
DataHandler.php: PHP class to handle database operations.
classes/: Directory containing additional classes used in the application.
css/: Directory for CSS styles used in the HTML pages.
database.sql: SQL file containing the database schema.

\*\*\*\*\*\* EXPLANATION FOR THE CODE \*\*\*\*\*\*

<?php
require_once '../classes/Authentication.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $rememberMe = isset($_POST['remember_me']) ? true : false;

    $user = new Authentication();
    if ($user->login($username, $password, $rememberMe)) {
        if ($_SESSION['role'] === 'Admin' || (isset($_COOKIE['role']) && $_COOKIE['role'] === 'Admin')) {
            header('Location: ../staff/staff_home.php'); 
        } else {
            header('Location: ../customer/customer_home.php'); 
        }
        exit();
    } else {
        echo "Invalid credentials.";
    }
}
?>

---> The above code in login page includes the Authentication class, which contains methods related to user authentication.
---> if ($_SESSION['role'] === 'Admin' || (isset($\_COOKIE['role']) && $_COOKIE['role'] === 'Admin')) {: Checks if the user's role is 'Admin'. This check is done using both the session variable and a cookie (in case the user selected "Remember Me").
header('Location: ../staff/staff_home.php');: If the user is an Admin, they are redirected to the staff home page.
header('Location: ../customer/customer_home.php');: If the user is not an Admin, they are redirected to the customer home page.
---> $user = new Authentication();: Creates a new instance of the Authentication class.
if ($user->login($username, $password, $rememberMe)) {: Calls the login method of the Authentication class to authenticate the user with the provided username, password, and remember me option. If the login is successful, the code inside this block will execute.
