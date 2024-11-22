<?php
session_start();
$userId = $_SESSION['id'];
$conn = mysqli_connect("localhost", "root", "12233445", "lab_7");

$sql = "SELECT username FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $username);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if (isset($_POST["logout"])) {
    session_destroy();
    header("Location: ../pages/authorization_page.html");
    exit();
}


if (isset($_POST["save_name"])) {
    $name = $_POST["name"];
    $sql = "UPDATE users SET username = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $name, $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if (isset($_POST["save_email"])) {
    $email = $_POST["email"];
    $sql = "UPDATE users SET email = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $email, $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if (isset($_POST["save_password"])) {
    $password = $_POST["password"];
    $hashed_password = md5($password);
    $sql = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $hashed_password, $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="../css/index.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="content">
    <p class="welcome"><?php echo "Привіт " . $username . "!"; ?></p>
    <div class="personal_office">
        <h1>Особистий кабінет</h1>
        <form id="changeName" method="post">
            <p>Змінити ім'я</p>
            <input type="text" name="name">
            <input type="submit" name="save_name" id="save_name" value="Зберегти"><br>
            <span class="error-message" id="nameError"></span>
        </form>
        <form id="changeEmail" method="post">
            <p>Змінити пошту</p>
            <input type="text" name="email">
            <input type="submit" name="save_email" id="save_email" value="Зберегти"><br>
            <span class="error-message" id="emailError"></span>
        </form>
        <form  id="changePassword" method="post">
            <p>Змінити пароль</p>
            <input type="text" name="password">
            <input type="submit" name="save_password" id="save_password" value="Зберегти"><br>
            <span class="error-message" id="passwordError"></span>
        </form>
        <form method="post">
            <input type="submit" name="logout" id="logout" value="Вийти"></input>
        </form>
    </div>
</div>

<script>
    $("#changeName").submit(function(event) {
        let valid = true;
        $(".error-message").text("").hide();

        const name = $("input[name='name']").val();
        if (name === "") {
            $("#nameError").text("Ім'я не може бути порожнім.").show();
            valid = false;
        }

        if (!valid) {
            event.preventDefault();
        }
    });

    $("#changeEmail").submit(function(event) {
        let valid = true;
        $(".error-message").text("").hide();

        const email = $("input[name='email']").val();
        const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (email === "") {
            $("#emailError").text("Електронна пошта не може бути порожньою.").show();
            valid = false;
        } else if (!emailPattern.test(email)) {
            $("#emailError").text("Неправильний формат електронної пошти.").show();
            valid = false;
        }

        if (!valid) {
            event.preventDefault();
        }
    });

    $("#changePassword").submit(function(event) {
        let valid = true;
        $(".error-message").text("").hide();

        const password = $("input[name='password']").val();
        if (password === "") {
            $("#passwordError").text("Пароль не може бути порожнім.").show();
            valid = false;
        }

        if (!valid) {
            event.preventDefault();
        }
    });

</script>
</body>
</html>
