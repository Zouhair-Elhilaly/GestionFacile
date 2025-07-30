<?php
session_start();

require_once '../include/config.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']) ? 1 : 0;

    // Validate inputs
    if (empty($email)) {
        $_SESSION['email_error'] = "Email required";
        $_SESSION['password'] = $password;
    }else if(filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $_SESSION['email_error'] = "Invalid email format";
        $_SESSION['email'] = $email;
        $_SESSION['password'] = $password;
    }

    if(empty($password)){
       $_SESSION['password_error'] = "Password required";
        $_SESSION['email'] = "$email";
    }

    // Prepare SQL query to check employee existence
    $stmt = $conn1->prepare("SELECT * FROM employé WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();

    $employee = $stmt->get_result()->fetch_assoc();

    if ($employee) {
        // Verify password (assuming passwords are hashed in the database)
        if (password_verify($password, $employee['mot_de_passe_em'])) {
            // Start session and set session variables
            session_start();
            $_SESSION['employee_id'] = $employee['id'];
            $_SESSION['employee_email'] = $employee['email'];
            $_SESSION['employee_name'] = $employee['nom'];
            
            // Set remember me cookie if checked
            if ($remember) {
                $cookie_value = base64_encode($employee['id'] . ':' . $employee['email']);
                setcookie('remember_employee', $cookie_value, time() + (86400 * 30), "/"); // 30 days
            }

            $_SESSION['email_employe'] = $email;
            
            // Redirect to dashboard or home page
            header("Location: ../user/home.php");
            exit();
        } 
    }else{
        $_SESSION['user'] = 'invalid data';
    }
}

// If not a POST request or validation fails, redirect back to login
header("Location:../index.php");
exit();
?>