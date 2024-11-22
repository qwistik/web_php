<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    echo"Hi";

    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $hashed_password = md5($password);

    $conn = mysqli_connect("localhost", "root", "12233445", "lab_7");

    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ? OR email = ?");
    mysqli_stmt_bind_param($stmt, "ss", $name, $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        $message = "Користувач з таким ім'ям або email вже існує.";
        echo json_encode(["success" => false, "message" => $message]);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        exit();
    }

    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($conn, "INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hashed_password);


    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Помилка при реєстрації."]);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
