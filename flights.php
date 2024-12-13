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

// Обработка формы
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    if ($action == 'add') {
        // Добавление полета
        $passenger_id = $_POST['passenger_id'];
        $route_id = $_POST['route_id'];
        $flight_date = $_POST['flight_date'];
        $sql = "INSERT INTO Flights (passenger_id, route_id, flight_date) VALUES ($passenger_id, $route_id, '$flight_date')";
        if ($conn->query($sql) === TRUE) {
            echo "Полет добавлен";
        } else {
            echo "Ошибка: " . $conn->error;
        }
    } elseif ($action == 'edit') {
        // Редактирование полета
        $flight_id = $_POST['flight_id'];
        $passenger_id = $_POST['passenger_id'];
        $route_id = $_POST['route_id'];
        $flight_date = $_POST['flight_date'];
        $sql = "UPDATE Flights SET passenger_id = $passenger_id, route_id = $route_id, flight_date = '$flight_date' WHERE flight_id = $flight_id";
        if ($conn->query($sql) === TRUE) {
            echo "Полет обновлен";
        } else {
            echo "Ошибка: " . $conn->error;
        }
    } elseif ($action == 'delete') {
        // Удаление полета
        $flight_id = $_POST['flight_id'];
        $sql = "DELETE FROM Flights WHERE flight_id = $flight_id";
        if ($conn->query($sql) === TRUE) {
            echo "Полет удален";
        } else {
            echo "Ошибка: " . $conn->error;
        }
    }
}

// Получаем список полетов
$sql = "SELECT flight_id, passenger_id, route_id, flight_date FROM Flights";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление полетами</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Управление полетами</h1>

    <!-- Форма для добавления полета -->
    <h2>Добавить полет</h2>
    <form method="POST" action="flights.php">
        <input type="hidden" name="action" value="add">
        <label for="passenger_id">ID пассажира:</label>
        <input type="number" name="passenger_id" id="passenger_id" required>
        <label for="route_id">ID маршрута:</label>
        <input type="number" name="route_id" id="route_id" required>
        <label for="flight_date">Дата полета:</label>
        <input type="date" name="flight_date" id="flight_date" required>
        <button type="submit">Добавить</button>
    </form>

    <!-- Список полетов -->
    <h2>Список полетов</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>ID пассажира</th>
            <th>ID маршрута</th>
            <th>Дата полета</th>
            <th>Действия</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["flight_id"] . "</td>";
                echo "<td>" . $row["passenger_id"] . "</td>";
                echo "<td>" . $row["route_id"] . "</td>";
                echo "<td>" . $row["flight_date"] . "</td>";
                echo "<td>
                        <form method='POST' action='flights.php' style='display:inline;'>
                            <input type='hidden' name='action' value='edit'>
                            <input type='hidden' name='flight_id' value='" . $row["flight_id"] . "'>
                            <input type='number' name='passenger_id' value='" . $row["passenger_id"] . "'>
                            <input type='number' name='route_id' value='" . $row["route_id"] . "'>
                            <input type='date' name='flight_date' value='" . $row["flight_date"] . "'>
                            <button type='submit'>Редактировать</button>
                        </form>
                        <form method='POST' action='flights.php' style='display:inline;'>
                            <input type='hidden' name='action' value='delete'>
                            <input type='hidden' name='flight_id' value='" . $row["flight_id"] . "'>
                            <button type='submit'>Удалить</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Нет данных</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>