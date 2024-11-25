<?php

header('Content-Type: application/json');

$host = 'localhost';
$user = 'root';
$password = '12233445';
$database = 'lab8';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Не вдалося підключитися до бази даних']));
}

$apiKey = '682bc61a85f237b2cd3b04bbe7bb919f';

$action = $_GET['action'] ?? $_POST['action'] ?? null;

if ($action === 'getCities') {
    $query = $_GET['query'] ?? null; // Отримуємо текстовий запит
    if (!$query) {
        echo json_encode(['success' => false, 'error' => 'Параметр query не задано']);
        exit;
    }

    header('Content-Type: application/json');
    $response = file_get_contents('https://api.novaposhta.ua/v2.0/json/', false, stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/json',
            'content' => json_encode([
                'apiKey' => $apiKey,
                'modelName' => 'Address',
                'calledMethod' => 'getSettlements',
                'methodProperties' => [
                    'FindByString' => $query, // Пошук за текстом
                    'Limit' => 10
                ],
            ]),
        ],
    ]));
    echo $response;
    exit;
}



if ($action === 'getBranches') {
    $cityRef = $_GET['cityRef'] ?? ''; // Отримання cityRef з параметрів запиту

    // Перевірка, чи cityRef не порожній
    if (empty($cityRef)) {
        echo json_encode(['error' => 'CityRef is not specified!!']);
        exit;
    }

    // Запит до API для отримання відділень
    $response = file_get_contents('https://api.novaposhta.ua/v2.0/json/', false, stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/json',
            'content' => json_encode([
                'apiKey' => $apiKey,
                'modelName' => 'AddressGeneral',
                'calledMethod' => 'getWarehouses',
                'methodProperties' => ['CityName' => $cityRef] // Виправлено, без .CASE_LOWER
            ]),
        ]
    ]));
    echo $response;
    exit;
}


if ($action === 'saveOrder') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        echo json_encode(['error' => 'Невірний формат даних']);
        exit;
    }

    // Перевірка наявності кожного поля
    $orderNumber = $data['orderNumber'] ?? null;
    $weight = $data['weight'] ?? null;
    $city = $data['city'] ?? null;
    $deliveryType = $data['deliveryType'] ?? null;
    $branch = $data['branch'] ?? null;

    if (!$orderNumber || !$weight || !$city || !$deliveryType || !$branch) {
        echo json_encode(['error' => 'Усі поля повинні бути заповнені']);
        exit;
    }


    $stmt = $conn->prepare('INSERT INTO orders (order_number, weight, city_ref, delivery_type, branch_ref) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param('sdsss', $orderNumber, $weight, $city, $deliveryType, $branch);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Замовлення успішно збережено']);
    } else {
        echo json_encode(['error' => 'Не вдалося зберегти замовлення']);
    }

    $stmt->close();
    $conn->close();
    exit;
}

echo json_encode(['error' => 'Невідома дія']);
