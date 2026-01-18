<?php
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$token = $data["token"] ?? null;
$playlistId = $data["playlist_id"] ?? null;
$trackUri = $data["track_uri"] ?? null;

if (!$token || !$playlistId || !$trackUri) {
    echo json_encode(["error" => "Missing data"]);
    exit;
}

$opts = [
    "http" => [
        "method" => "POST",
        "header" => [
            "Authorization: Bearer $token",
            "Content-Type: application/json"
        ],
        "content" => json_encode([
            "uris" => [$trackUri]
        ])
    ]
];

$url = "https://api.spotify.com/v1/playlists/$playlistId/tracks";
$response = file_get_contents($url, false, stream_context_create($opts));

echo json_encode(["success" => (bool)$response]);
