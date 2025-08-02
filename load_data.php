<?php
$host = 'MySQL-8.4';
$db = 'blog';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("CREATE TABLE IF NOT EXISTS posts (
        id INT PRIMARY KEY,
        title TEXT,
        body TEXT,
        userId INT
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS comments (
        id INT PRIMARY KEY,
        postId INT,
        name TEXT,
        email TEXT,
        body TEXT,
        FOREIGN KEY (postId) REFERENCES posts(id)
    )");


    $postsJson = file_get_contents('https://jsonplaceholder.typicode.com/posts');
    $posts = json_decode($postsJson, true);

    $stmtPost = $pdo->prepare("INSERT INTO posts (id, title, body, userId) VALUES (:id, :title, :body, :userId)");

    $postCount = 0;


    foreach ($posts as $post) {
        $stmtPost->execute([
            ':id' => $post['id'],
            ':title' => $post['title'],
            ':body' => $post['body'],
            ':userId' => $post['userId']
        ]);
        $postCount++;
    }

    $commentsJson = file_get_contents('https://jsonplaceholder.typicode.com/comments');
    $comments = json_decode($commentsJson, true);

    $stmtComment = $pdo->prepare("INSERT INTO comments (id, postId, name, email, body) VALUES (:id, :postId, :name, :email, :body)");

    $commentCount = 0;

    foreach ($comments as $comment) {
        $stmtComment->execute([
            ':id' => $comment['id'],
            ':postId' => $comment['postId'],
            ':name' => $comment['name'],
            ':email' => $comment['email'],
            ':body' => $comment['body']
        ]);
        $commentCount++;
    }

    echo "Загружено $postCount записей и $commentCount комментариев.\n";

} catch (PDOException $e) {
    echo "База данных уже загружена";
}
