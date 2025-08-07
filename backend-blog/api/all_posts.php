<?php
require '../connection.php';

header('Content-Type: application/json');

$sql = "
    SELECT posts.id, posts.title, posts.content, posts.created_at, users.name AS author,
           (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) AS comment_count
    FROM posts
    JOIN users ON posts.user_id = users.id
    ORDER BY posts.created_at DESC
";

$stmt = $pdo->query($sql);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($posts);
