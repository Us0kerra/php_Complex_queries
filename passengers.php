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
        // Добавление пассажира
        $last_name = $_POST['last_name'];
        $sql = "INSERT INTO Passengers (last_name) VALUES ('$last_name')";
        if ($conn->query($sql) === TRUE) {
            echo "Пассажир добавлен";
        } else {
            echo "Ошибка: " . $conn->error;
        }
    } elseif ($action == 'edit') {
        // Редактирование пассажира
        $passenger_id = $_POST['passenger_id'];
        $last_name = $_POST['last_name'];
        $sql = "UPDATE Passengers SET last_name = '$last_name' WHERE passenger_id = $passenger_id";
        if ($conn->query($sql) === TRUE) {
            echo "Пассажир обновлен";
        } else {
            echo "Ошибка: " . $conn->error;
        }
    } elseif ($action == 'delete') {
        // Удаление пассажира
        $passenger_id = $_POST['passenger_id'];
        $sql = "DELETE FROM Passengers WHERE passenger_id = $passenger_id";
        if ($conn->query($sql) === TRUE) {
            echo "Пассажир удален";
        } else {
            echo "Ошибка: " . $conn->error;
        }
    }
}

// Получаем список пассажиров
$sql = "SELECT passenger_id, last_name FROM Passengers";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление пассажирами</title>
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
    <h1>Управление пассажирами</h1>

    <!-- Форма для добавления пассажира -->
    <h2>Добавить пассажира</h2>
    <form method="POST" action="passengers.php">
        <input type="hidden" name="action" value="add">
        <label for="last_name">Фамилия:</label>
        <input type="text" name="last_name" id="last_name" required>
        <button type="submit">Добавить</button>
    </form>

    <!-- Список пассажиров -->
    <h2>Список пассажиров</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Фамилия</th>
            <th>Действия</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["passenger_id"] . "</td>";
                echo "<td>" . $row["last_name"] . "</td>";
                echo "<td>
                        <form method='POST' action='passengers.php' style='display:inline;'>
                            <input type='hidden' name='action' value='edit'>
                            <input type='hidden' name='passenger_id' value='" . $row["passenger_id"] . "'>
                            <input type='text' name='last_name' value='" . $row["last_name"] . "'>
                            <button type='submit'>Редактировать</button>
                        </form>
                        <form method='POST' action='passengers.php' style='display:inline;'>
                            <input type='hidden' name='action' value='delete'>
                            <input type='hidden' name='passenger_id' value='" . $row["passenger_id"] . "'>
                            <button type='submit'>Удалить</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>Нет данных</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>