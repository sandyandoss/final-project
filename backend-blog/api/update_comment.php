<?php
header("Content-Type: application/json");

$mysqli = new mysqli("localhost", "root", "", "blog_api");

if ($mysqli->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "DB connection failed"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["comment_id"]) || !isset($data["content"])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing comment_id or content"]);
    exit;
}

$comment_id = intval($data["comment_id"]);
$content = $mysqli->real_escape_string($data["content"]);

$sql = "UPDATE comments SET content = '$content' WHERE id = $comment_id";

if ($mysqli->query($sql)) {
    echo json_encode(["message" => "Comment updated successfully"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Update failed"]);
}
?>
