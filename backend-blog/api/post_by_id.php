<?php
header("Content-Type: application/json");

$mysqli = new mysqli("localhost", "root", "", "blog_api");

if ($mysqli->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "DB connection failed"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["post_id"])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing post_id"]);
    exit;
}

$post_id = intval($data["post_id"]);

// Get post
$post_sql = "SELECT p.id, p.title, p.content, p.created_at, u.name AS author
             FROM posts p JOIN users u ON p.user_id = u.id
             WHERE p.id = $post_id";
$post_result = $mysqli->query($post_sql);

if ($post_result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(["error" => "Post not found"]);
    exit;
}

$post = $post_result->fetch_assoc();

// Get comments
$comment_sql = "SELECT c.id, c.content, c.created_at, u.name AS author
                FROM comments c JOIN users u ON c.user_id = u.id
                WHERE c.post_id = $post_id
                ORDER BY c.created_at DESC
                LIMIT 15";
$comment_result = $mysqli->query($comment_sql);
$comments = [];

while ($row = $comment_result->fetch_assoc()) {
    $comments[] = $row;
}

$post["comments"] = $comments;

echo json_encode($post);
?>
