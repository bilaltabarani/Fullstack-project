<?php
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$token = $data["token"];
if (!$token) die(json_encode(["error" => "No token received"]));

function spotifyGET($url, $token) {
    $opts = [
        "http" => [
            "method" => "GET",
            "header" => "Authorization: Bearer $token"
        ]
    ];
    return json_decode(file_get_contents($url, false, stream_context_create($opts)), true);
}

function spotifyPOST($url, $token, $body) {
    $opts = [
        "http" => [
            "method" => "POST",
            "header" => "Authorization: Bearer $token\r\nContent-Type: application/json",
            "content" => json_encode($body)
        ]
    ];
    return json_decode(file_get_contents($url, false, stream_context_create($opts)), true);
}

$user = spotifyGET("https://api.spotify.com/v1/me", $token);
$userId = $user["id"];

$topTracks = spotifyGET("https://api.spotify.com/v1/me/top/tracks?limit=5", $token);
$trackUris = array_map(fn($t) => $t["uri"], $topTracks["items"]);

$playlist = spotifyPOST("https://api.spotify.com/v1/users/$userId/playlists", $token, [
    "name" => "Your Top 5 Tracks",
    "description" => "Created automatically!",
    "public" => false
]);
$playlistId = $playlist["id"];

$added = spotifyPOST("https://api.spotify.com/v1/playlists/$playlistId/tracks", $token, [
    "uris" => $trackUris
]);

echo json_encode([
    "playlist" => $playlist,
    "added" => $added
]);
