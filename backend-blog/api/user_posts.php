<?php
// NOTE: Default MySQL password is blank. Change if needed for your setup.

header("Content-Type: application/json");

$mysqli = new mysqli("localhost", "root", "", "blog_api");

if ($mysqli->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "DB connection failed"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["user_id"])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing user_id"]);
    exit;
}

$user_id = intval($data["user_id"]);

$sql = "SELECT id, title, content, created_at
        FROM posts
        WHERE user_id = $user_id
        ORDER BY created_at DESC
        LIMIT 10";

$result = $mysqli->query($sql);
$posts = [];

while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}

echo json_encode($posts);
?>
