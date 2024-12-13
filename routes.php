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
        // Добавление маршрута
        $city = $_POST['city'];
        $price = $_POST['price'];
        $sql = "INSERT INTO Routes (city, price) VALUES ('$city', $price)";
        if ($conn->query($sql) === TRUE) {
            echo "Маршрут добавлен";
        } else {
            echo "Ошибка: " . $conn->error;
        }
    } elseif ($action == 'edit') {
        // Редактирование маршрута
        $route_id = $_POST['route_id'];
        $city = $_POST['city'];
        $price = $_POST['price'];
        $sql = "UPDATE Routes SET city = '$city', price = $price WHERE route_id = $route_id";
        if ($conn->query($sql) === TRUE) {
            echo "Маршрут обновлен";
        } else {
            echo "Ошибка: " . $conn->error;
        }
    } elseif ($action == 'delete') {
        // Удаление маршрута
        $route_id = $_POST['route_id'];
        $sql = "DELETE FROM Routes WHERE route_id = $route_id";
        if ($conn->query($sql) === TRUE) {
            echo "Маршрут удален";
        } else {
            echo "Ошибка: " . $conn->error;
        }
    }
}

// Получаем список маршрутов
$sql = "SELECT route_id, city, price FROM Routes";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление маршрутами</title>
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
    <h1>Управление маршрутами</h1>

    <!-- Форма для добавления маршрута -->
    <h2>Добавить маршрут</h2>
    <form method="POST" action="routes.php">
        <input type="hidden" name="action" value="add">
        <label for="city">Город:</label>
        <input type="text" name="city" id="city" required>
        <label for="price">Цена:</label>
        <input type="number" name="price" id="price" step="0.01" required>
        <button type="submit">Добавить</button>
    </form>

    <!-- Список маршрутов -->
    <h2>Список маршрутов</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Город</th>
            <th>Цена</th>
            <th>Действия</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["route_id"] . "</td>";
                echo "<td>" . $row["city"] . "</td>";
                echo "<td>" . $row["price"] . "</td>";
                echo "<td>
                        <form method='POST' action='routes.php' style='display:inline;'>
                            <input type='hidden' name='action' value='edit'>
                            <input type='hidden' name='route_id' value='" . $row["route_id"] . "'>
                            <input type='text' name='city' value='" . $row["city"] . "'>
                            <input type='number' name='price' step='0.01' value='" . $row["price"] . "'>
                            <button type='submit'>Редактировать</button>
                        </form>
                        <form method='POST' action='routes.php' style='display:inline;'>
                            <input type='hidden' name='action' value='delete'>
                            <input type='hidden' name='route_id' value='" . $row["route_id"] . "'>
                            <button type='submit'>Удалить</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Нет данных</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>