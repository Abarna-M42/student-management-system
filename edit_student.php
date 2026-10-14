<?php

require_once "auth.php";
require_once "db.php"; 
$message = "";
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    exit("Invalid student ID.");
}

$stmt = $pdo->prepare("SELECT * FROM students WHERE id = :id");
$stmt->execute(["id" => $id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    exit("Student not found.");
}

$name = $student["name"];
$email = $student["email"];
$age = $student["age"];
$course = $student["course"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $age = filter_input(INPUT_POST, "age", FILTER_VALIDATE_INT);
    $course = trim($_POST["course"] ?? "");

    if (
        $name === "" || $course === "" ||
        $age === false || $age === null || $age < 1
    ) {
        $message = "Please enter valid details.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
    } else {
        try {
            $sql = "UPDATE students
                    SET name = :name, email = :email,
                        age = :age, course = :course
                    WHERE id = :id";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                "name" => $name,
                "email" => $email,
                "age" => $age,
                "course" => $course,
                "id" => $id
            ]);

            header("Location: index.php?message=updated");
            exit;

        } catch (PDOException $e) {
            if ($e->getCode() === "23000") {
                $message = "This email is already registered.";
            } else {
                error_log($e->getMessage());
                $message = "Unable to update student details.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Student</title>
</head>
<body>

    <h1>Edit Student Details</h1>

    <?php if ($message !== ""): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Name:</label>
        <input type="text" name="name"
               value="<?= htmlspecialchars($name) ?>" required>
        <br><br>

        <label>Email:</label>
        <input type="email" name="email"
               value="<?= htmlspecialchars($email) ?>" required>
        <br><br>

        <label>Age:</label>
        <input type="number" name="age" min="1"
               value="<?= htmlspecialchars((string)$age) ?>" required>
        <br><br>

        <label>Course:</label>
        <input type="text" name="course"
               value="<?= htmlspecialchars($course) ?>" required>
        <br><br>

        <button type="submit">Update Student</button>
    </form>

    <p><a href="index.php">Back to Student List</a></p>

</body>
</html>
