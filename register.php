<?php
session_start();
require_once "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if (
        $name === "" ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        strlen($password) < 8
    ) {
        $message = "Enter a name, valid email, and password with at least 8 characters.";
    } else {
        try {
            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $stmt = $pdo->prepare(
                "INSERT INTO users (name, email, password)
                 VALUES (:name, :email, :password)"
            );

            $stmt->execute([
                "name" => $name,
                "email" => $email,
                "password" => $hashedPassword
            ]);

            $message = "Registration successful! You can now log in.";
        } catch (PDOException $e) {
            if ($e->getCode() === "23000") {
                $message = "This email is already registered.";
            } else {
                error_log($e->getMessage());
                $message = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Register</h1>

    <p><?= htmlspecialchars($message) ?></p>

    <form method="POST">
        <label>Name</label>
        <input type="text" name="name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password"
               minlength="8" required>

        <button type="submit">Create Account</button>
    </form>

    <p><a href="login.php">Already registered? Login</a></p>
</div>
</body>
</html>
