<?php
require '../connection.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$postId = $data['id'] ?? null;

if (!$postId) {
    http_response_code(400);
    echo json_encode(['error' => 'Post ID required']);
    exit;
}

$sql = "
    SELECT posts.*, users.name AS author
    FROM posts
    JOIN users ON posts.user_id = users.id
    WHERE posts.id = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$postId]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    http_response_code(404);
    echo json_encode(['error' => 'Post not found']);
    exit;
}

// Get latest 15 comments
$commentStmt = $pdo->prepare("
    SELECT comments.*, users.name AS commenter
    FROM comments
    JOIN users ON comments.user_id = users.id
    WHERE comments.post_id = ?
    ORDER BY comments.created_at DESC
    LIMIT 15
");
$commentStmt->execute([$postId]);
$comments = $commentStmt->fetchAll(PDO::FETCH_ASSOC);

$post['comments'] = $comments;

echo json_encode($post);
