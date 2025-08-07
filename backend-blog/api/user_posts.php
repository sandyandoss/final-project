<?php
require '../connection.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$userId = $data['user_id'] ?? null;

if (!$userId) {
    http_response_code(400);
    echo json_encode(['error' => 'User ID required']);
    exit;
}

$sql = "
    SELECT id, title, content, created_at
    FROM posts
    WHERE user_id = ?
    ORDER BY created_at DESC
    LIMIT 10
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$userId]);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($posts);
