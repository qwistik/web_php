<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);

    // Функція для перевірки, чи є рядок буквенним 
    function isValidString($str) {
        return preg_match("/^[\p{L}']+$/u", $str);
    }

    if (empty($name) || empty($surname)) {
        echo "Будь ласка, заповніть всі поля форми!<br><br>";
    } else {
        if (isValidString($name) && isValidString($surname)) {
            echo "Привіт, " . htmlspecialchars($name) . " " . htmlspecialchars($surname) . "!";
        } else {
            echo "Ім'я та прізвище повинні містити лише літери.";
        }
    }
} else {
    echo "Дані не отримано.";
}











?>

    <h2>Виведення "Hello, World!"</h2>
    <?php
    // Виведення на екран повідомлення "Hello, World!"
    echo "Hello, World!";
    ?>

    <h2>Змінні та типи даних</h2>
    <?php
    // Оголошення змінних різних типів
    $stringVar = "Це рядок"; // Рядкова змінна
    $intVar = 123; // Ціле число
    $floatVar = 12.34; // Число з плаваючою комою
    $boolVar = true; // Булеве значення

    // Виведення значень змінних
    echo $stringVar . "<br>";
    echo $intVar . "<br>";
    echo $floatVar . "<br>";
    echo $boolVar . "<br>";

    // Виведення типу кожної змінної
    echo "Типи змінних:<br>";
    var_dump($stringVar);
    echo "<br>";
    var_dump($intVar);
    echo "<br>";
    var_dump($floatVar);
    echo "<br>";
    var_dump($boolVar);
    ?>

    <h2>Конкатенація рядків</h2>
    <?php
    // Створення двох рядкових змінних
    $firstName = "Іван";
    $lastName = "Петров";

    // Конкатенація змінних і виведення результату
    $fullName = $firstName . " " . $lastName;
    echo "Повне ім'я: " . $fullName;
    ?>

    <h2>Умовні конструкції</h2>
    <?php
    // Створення змінної з числовим значенням
    $number = 7;

    // Перевірка, чи є число парним або непарним
    if ($number % 2 == 0) {
        echo "$number є парним числом.";
    } else {
        echo "$number є непарним числом.";
    }
    ?>

    <h2>Цикли</h2>
    <?php
    // Цикл for для виведення чисел від 1 до 10
    echo "Числа від 1 до 10:<br>";
    for ($i = 1; $i <= 10; $i++) {
        echo $i . " ";
    }
    echo "<br><br>";

    // Цикл while для виведення чисел від 10 до 1
    echo "Числа від 10 до 1:<br>";
    $j = 10;
    while ($j >= 1) {
        echo $j . " ";
        $j--;
    }
    ?>

    <h2>Масиви</h2>
    <?php
    // Створення асоціативного масиву з інформацією про студента
    $student = [
        "ім'я" => "Олександр",
        "прізвище" => "Іваненко",
        "вік" => 21,
        "спеціальність" => "Комп'ютерні науки"
    ];

    // Виведення кожного елемента масиву
    echo "Ім'я: " . $student["ім'я"] . "<br>";
    echo "Прізвище: " . $student["прізвище"] . "<br>";
    echo "Вік: " . $student["вік"] . "<br>";
    echo "Спеціальність: " . $student["спеціальність"] . "<br>";

    // Додавання нового елемента до масиву
    $student["середній бал"] = 4.5;

    // Виведення оновленого масиву
    echo "Середній бал: " . $student["середній бал"];
    ?>