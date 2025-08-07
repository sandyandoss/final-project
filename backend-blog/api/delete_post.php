<?php
require '../connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid method']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$postId = $data['id'] ?? null;

if (!$postId) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing post ID']);
    exit;
}

// First delete comments on that post
$pdo->prepare("DELETE FROM comments WHERE post_id = ?")->execute([$postId]);

// Then delete the post
$stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
$success = $stmt->execute([$postId]);

echo json_encode(['deleted' => $success]);
