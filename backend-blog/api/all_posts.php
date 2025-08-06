<?php
// NOTE: Default MySQL password is blank. Change if needed for your setup.


$mysqli = new mysqli("localhost", "root", "", "blog_api");

if ($mysqli->connect_error) {
    header("Content-Type: application/json");
    http_response_code(500);
    echo json_encode(["error" => "DB connection failed"]);
    exit;
}

$sql = "SELECT p.id, p.title, p.content, p.created_at, u.name AS author,
        (SELECT COUNT(*) FROM comments c WHERE c.post_id = p.id) AS comment_count
        FROM posts p
        JOIN users u ON p.user_id = u.id
        ORDER BY p.created_at DESC";

$result = $mysqli->query($sql);
$posts = [];

while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}

echo json_encode($posts);
?>
