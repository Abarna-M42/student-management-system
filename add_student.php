
<?php
require_once "auth.php";
require_once "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $age = filter_input(INPUT_POST, "age", FILTER_VALIDATE_INT);
    $course = trim($_POST["course"] ?? "");

    if ($name === "" || $email === "" || $age === false ||
        $age === null || $age < 1 || $course === "") {
        $message = "Please enter valid details in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
    } else {
        try {
            $sql = "INSERT INTO students (name, email, age, course)
                    VALUES (:name, :email, :age, :course)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                "name" => $name,
                "email" => $email,
                "age" => $age,
                "course" => $course
            ]);

            header("Location: index.php?message=added");
            exit;

        } catch (PDOException $e) {
            if ($e->getCode() === "23000") {
                $message = "This email address is already registered.";
            } else {
                error_log($e->getMessage());
                $message = "Unable to save the student. Please try again.";
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
    <title>Add Student</title>
</head>
<body>

    <h1>Add New Student</h1>

    <?php if ($message !== ""): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST" action="add_student.php">
        <label>Name:</label>
        <input type="text" name="name" required>
        <br><br>

        <label>Email:</label>
        <input type="email" name="email" required>
        <br><br>

        <label>Age:</label>
        <input type="number" name="age" min="1" required>
        <br><br>

        <label>Course:</label>
        <input type="text" name="course" required>
        <br><br>

        <button type="submit">Save Student</button>
    </form>

    <br>
    <a href="index.php">Back to Student List</a>

</body>
</html>
