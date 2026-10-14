<?php
require_once "auth.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Method not allowed.");
}

$submittedToken = $_POST["csrf_token"] ?? "";
$sessionToken = $_SESSION["csrf_token"] ?? "";

if (
    !is_string($submittedToken) ||
    !is_string($sessionToken) ||
    $sessionToken === "" ||
    !hash_equals($sessionToken, $submittedToken)
) {
    http_response_code(403);
    exit("Invalid security token. Please refresh the page and try again.");
}

require_once "db.php";

// Keep your existing ID validation and prepared DELETE query below.


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("Invalid request.");
}

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    exit("Invalid student ID.");
}

$stmt = $pdo->prepare(
    "DELETE FROM students WHERE id = :id"
);

$stmt->execute(["id" => $id]);

if ($stmt->rowCount() > 0) {
    header("Location: index.php?message=deleted");
    exit;
}

header("Location: index.php?message=notfound");
exit;
?>
