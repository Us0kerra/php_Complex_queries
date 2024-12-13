<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Авиабилеты</title>
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
        .buttons {
            margin-bottom: 20px;
        }
        .buttons button {
            margin-right: 10px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Авиабилеты: Запросы</h1>
    <div class="buttons">
        <button onclick="loadQuery('query1')">Список пассажиров с количеством полетов</button>
        <button onclick="loadQuery('query2')">Пассажиры с аэропортами и количеством полетов</button>
        <button onclick="loadQuery('query3')">Список аэропортов с указанием, сколько пассажиров совершили до него полеты</button>
        <button onclick="loadQuery('query4')">Пассажиры с общим количеством полетов</button>
    </div>

    <div id="result"></div>

    <script>
        async function loadQuery(queryType) {
            try {
                const response = await fetch(`queries.php?query=${queryType}`);
                if (!response.ok) {
                    throw new Error(`Ошибка HTTP: ${response.status}`);
                }
                const data = await response.text();
                document.getElementById("result").innerHTML = data;
            } catch (error) {
                console.error('Произошла ошибка:', error);
                document.getElementById("result").innerHTML = 'Произошла ошибка при загрузке данных.';
            }
        }
    </script>
</body>
</html>