<?php
header('Content-Type: application/json');
require "db_connection.php";

$data = json_decode(file_get_contents("php://input"), true);
$playlistId = $data['playlist_id'] ?? null;

if (!$playlistId) {
    echo json_encode(["error" => "Playlist ID missing"]);
    exit;
}

$stmt = $con->prepare("SELECT COUNT(*) FROM playlist_likes WHERE playlist_id = ?");
$stmt->bind_param("s", $playlistId);
$stmt->execute();
$stmt->bind_result($likeCount);
$stmt->fetch();
$stmt->close();

$stmt = $con->prepare("
    SELECT c.comment, a.username
    FROM playlist_comments c
    JOIN accounts a ON a.id = c.user_id
    WHERE c.playlist_id = ?
    ORDER BY c.created_at DESC
");
$stmt->bind_param("s", $playlistId);
$stmt->execute();
$result = $stmt->get_result();
$comments = [];
while ($row = $result->fetch_assoc()) {
    $comments[] = $row;
}
$stmt->close();

echo json_encode([
    "likes" => $likeCount,
    "comments" => $comments
]);
