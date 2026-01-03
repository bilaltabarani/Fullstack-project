<?php
header('Content-Type: application/json');
require "db_connection.php";

if (!isset($_SESSION['account_loggedin'])) {
    echo json_encode(["error" => "Not logged in"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$playlistId = $data['playlist_id'] ?? null;
$userId = $_SESSION['account_id'];

if (!$playlistId) {
    echo json_encode(["error" => "Playlist ID missing"]);
    exit;
}

$stmt = $con->prepare("
    INSERT INTO playlist_likes (user_id, playlist_id)
    VALUES (?, ?)
    ON DUPLICATE KEY UPDATE created_at = created_at
");
$stmt->bind_param("is", $userId, $playlistId);
$stmt->execute();
$stmt->close();

echo json_encode(["message" => "Playlist liked"]);
