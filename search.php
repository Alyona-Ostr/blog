<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="search.css">
    <title>Search</title>
</head>
<body>
<?php

$host = 'MySQL-8.4';
$db = 'blog';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['search']) && strlen($_GET['search']) >= 3) {
        $search = '%' . $_GET['search'] . '%';
        $data = $pdo->prepare("SELECT p.title, c.body FROM posts p JOIN comments c ON p.id = c.postId WHERE c.body LIKE :search");
        $data->execute([':search' => $search]);

        echo "<table >";
        echo "<tr><th>Заголовок записи</th><th>Комментарий</th></tr>";

        while ($row = $data->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr >";
            echo "<td >" . htmlspecialchars($row['title']) . "</td>";
            echo "<td>" . htmlspecialchars($row['body']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Введите минимум 3 символа для поиска.";
    }

} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
}
?>
</body>
</html>
