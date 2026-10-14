<?php
require_once "auth.php";
require_once "db.php";


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}
$messages = [
    "added" => "Student added successfully!",
    "updated" => "Student updated successfully!",
    "deleted" => "Student deleted successfully!",
    "notfound" => "Student not found or already deleted."
];

$message = $_GET["message"] ?? "";

$search = trim($_GET["search"] ?? "");

if ($search !== "") {
    $sql = "SELECT * FROM students
            WHERE name LIKE :name
               OR email LIKE :email
            ORDER BY id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        "name" => "%$search%",
        "email" => "%$search%"
    ]);
} else {
    $stmt = $pdo->query(
        "SELECT * FROM students ORDER BY id ASC"
    );
}

$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <link rel="stylesheet" href="style.css">
    <?php if (isset($messages[$message])): ?>
    <p class="success-message">
        <?= htmlspecialchars($messages[$message]) ?>
    </p>
<?php endif; ?>
<p>Welcome, <?= htmlspecialchars($_SESSION["user_name"] ?? "User") ?>!
    <a href="logout.php">Logout</a>
</p>
</head>
<body>
    <div class="container">

    <h1>Student Management System</h1>

    <a href="add_student.php">Add New Student</a>

    <h3>Search Students</h3>

    <form method="GET" action="index.php">
        <input
            type="text"
            name="search"
            placeholder="Enter name or email"
            value="<?= htmlspecialchars($search) ?>"
        >
        <button type="submit">Search</button>
        <a href="index.php">Clear</a>
    </form>

    <br>

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Age</th>
            <th>Course</th>
            <th>Actions</th>
        </tr>

        <?php if (empty($students)): ?>
            <tr>
                <td colspan="6">No students found.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?= (int)$student["id"] ?></td>
                    <td><?= htmlspecialchars($student["name"]) ?></td>
                    <td><?= htmlspecialchars($student["email"]) ?></td>
                    <td><?= (int)$student["age"] ?></td>
                    <td><?= htmlspecialchars($student["course"]) ?></td>
                    <td>
                        <a href="edit_student.php?id=<?= (int)$student["id"] ?>">
                            Edit
                        </a>

                        <form action="delete_student.php" method="POST"onsubmit="return confirm
                        ('Are you sure you want to delete this student?');">
                        <input type="hidden" name="id"value="<?= (int) $student['id'] ?>">
                        <input type="hidden" name="csrf_token"value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                        <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
    </div>
</body>
</html>
