<?php
header('Content-Type: application/json');
require "db_connection.php";

if (!isset($_SESSION['account_loggedin'])) {
    echo json_encode(["error" => "Not logged in"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$playlistId = $data['playlist_id'] ?? null;
$comment = trim($data['comment'] ?? "");

if (!$playlistId || !$comment) {
    echo json_encode(["error" => "Missing playlist ID or comment"]);
    exit;
}

$stmt = $con->prepare("
    INSERT INTO playlist_comments (user_id, playlist_id, comment)
    VALUES (?, ?, ?)
");
$stmt->bind_param("iss", $_SESSION['account_id'], $playlistId, $comment);
$stmt->execute();
$stmt->close();

echo json_encode(["message" => "Comment added"]);
