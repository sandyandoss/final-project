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

if (!isset($data["post_id"])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing post_id"]);
    exit;
}

$post_id = intval($data["post_id"]);

// Delete post
$sql = "DELETE FROM posts WHERE id = $post_id";

if ($mysqli->query($sql)) {
    echo json_encode(["message" => "Post deleted successfully"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Deletion failed"]);
}
?>
