<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    session_start();

    // Sanitize user input
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $password = $_POST["password"];

    // Database connection
    $conn = mysqli_connect("localhost", "root", "12233445", "lab_7");

    if (!$conn) {
        echo json_encode(["success" => false, "message" => "Помилка підключення до бази даних"]);
        exit;
    }

    $sql = "SELECT password, id FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            mysqli_stmt_bind_result($stmt, $hashed_password, $id);
            mysqli_stmt_fetch($stmt);

            if (md5($password) === $hashed_password) {
                $_SESSION['id'] = $id;
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "message" => "Невірний пароль"]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Користувача з таким email не існує"]);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(["success" => false, "message" => "Помилка підготовки запиту"]);
    }

    mysqli_close($conn);
}
?>
