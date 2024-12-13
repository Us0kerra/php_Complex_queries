<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Aviasales";

// Создаем соединение
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверяем соединение
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Определяем тип запроса
$queryType = $_GET['query'];

if ($queryType == 'query1') {
    // Список пассажиров с количеством полетов
    $sql = "SELECT 
                p.passenger_id, 
                p.last_name, 
                COUNT(f.flight_id) AS total_flights
            FROM 
                Passengers p
            LEFT JOIN 
                Flights f ON p.passenger_id = f.passenger_id
            GROUP BY 
                p.passenger_id, p.last_name";
} elseif ($queryType == 'query2') {
    // Пассажиры с аэропортами и количеством полетов
    $sql = "SELECT 
                p.passenger_id, 
                p.last_name, 
                r.city, 
                COUNT(f.flight_id) AS flights_to_city
            FROM 
                Passengers p
            JOIN 
                Flights f ON p.passenger_id = f.passenger_id
            JOIN 
                Routes r ON f.route_id = r.route_id
            GROUP BY 
                p.passenger_id, p.last_name, r.city";
} elseif ($queryType == 'query3') {
    // Список аэропортов с указанием, сколько пассажиров совершили до него полеты (повторные полеты не учитывать)
    $sql = "SELECT 
                r.city AS airport, 
                COUNT(DISTINCT f.passenger_id) AS unique_passengers
            FROM 
                Routes r
            JOIN 
                Flights f ON r.route_id = f.route_id
            GROUP BY 
                r.city";
} elseif ($queryType == 'query4') {
    // Пассажиры с общим количеством полетов
    $sql = "SELECT 
                p.passenger_id, 
                p.last_name, 
                COUNT(f.flight_id) AS total_flights
            FROM 
                Passengers p
            LEFT JOIN 
                Flights f ON p.passenger_id = f.passenger_id
            GROUP BY 
                p.passenger_id, p.last_name";
} else {
    die("Неизвестный запрос");
}

// Выполняем запрос
$result = $conn->query($sql);

// Генерируем HTML-таблицу
if ($result->num_rows > 0) {
    $html = "<table>";
    $row = $result->fetch_assoc(); // Получаем первую строку для определения столбцов
    $html .= "<tr>";
    foreach ($row as $key => $value) {
        $html .= "<th>" . ucfirst($key) . "</th>"; // Заголовки столбцов
    }
    $html .= "</tr>";

    // Возвращаемся к началу результата
    $result->data_seek(0);

    // Генерируем строки данных
    while ($row = $result->fetch_assoc()) {
        $html .= "<tr>";
        foreach ($row as $value) {
            $html .= "<td>" . $value . "</td>";
        }
        $html .= "</tr>";
    }
    $html .= "</table>";
} else {
    $html = "<p>Нет данных</p>";
}

// Возвращаем HTML-таблицу
echo $html;

$conn->close();
?>