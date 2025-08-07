<?php
require '../connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid method']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$commentId = $data['id'] ?? null;
$content = $data['content'] ?? null;

if (!$commentId || !$content) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing comment ID or content']);
    exit;
}

$sql = "UPDATE comments SET content = ? WHERE id = ?";
$stmt = $pdo->prepare($sql);
$success = $stmt->execute([$content, $commentId]);

echo json_encode(['updated' => $success]);
