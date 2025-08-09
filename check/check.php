<?php
session_start();



require_once '../include/config.php';
require_once "../admin/functions/chiffre.php";
// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']) ? 1 : 0;

    // Validate inputs
    $errors = [];
    
    if (empty($email)) {
        $errors['email'] = "Email required";
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $errors['email'] = "Invalid email format";
    }

    if (empty($password)) {
        $errors['password'] = "Password required";
    }

    // If there are errors, store them in session and redirect back
    if (!empty($errors)) {
        $_SESSION['email_error'] = $errors['email'] ?? null;
        $_SESSION['password_error'] = $errors['password'] ?? null;
        $_SESSION['email_'] = $email;
        $_SESSION['password'] = $password;
        header("Location: ../index.php");
        exit();
    }

    // Check employee first
    $stmt = $conn1->prepare("SELECT * FROM employé WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $employee = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($employee && password_verify($password, $employee['mot_de_passe_em'])) {
        // Employee login successful
        $_SESSION['employee_id'] = $employee['id'];
        $_SESSION['email_employe'] = $employee['email'];
        $_SESSION['employee_name'] = $employee['nom'];
        $_SESSION['employee_priority'] = $employee['nom'];
        
        if ($remember) {
            $cookie_value = base64_encode($employee['id'] . ':' . $employee['email']);
            setcookie('remember_employee', $cookie_value, time() + (86400 * 30), "/");
        }

        header("Location:../user/home.php");
        exit();
    }

    // If not employee, check admin
    $stmt = $conn1->prepare("SELECT * FROM admin WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $admin = $stmt->get_result()->fetch_assoc();
    // $stmt->close();
    // $pass = password_hash($password , PASSWORD_BCRYPT);

    
    if ($admin && password_verify($password , $admin['mot_de_passe'])){
        // Admin login successful
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_email'] = $admin['email'];
        $_SESSION['admin_name'] = $admin['nom'];
        
        if ($remember) {
            $cookie_value = base64_encode($admin['id'] . ':' . $admin['email']);
            setcookie('remember_admin', $cookie_value, time() + (86400 * 30), "/");
        }

        header("Location: ../admin/acceuil.php");
        exit();
    
}

    // If neither employee nor admin matches
    $_SESSION['user'] = 'Email ou mot de passe incorrect';
    $_SESSION['email'] = $email;
    header("Location:../index.php");
    exit();
}

// If not a POST request, redirect back to login
header("Location:../index.php");
exit();
?>