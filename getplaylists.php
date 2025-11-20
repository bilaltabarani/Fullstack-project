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

$playlists = spotifyGET("https://api.spotify.com/v1/me/playlists?limit=50", $token);

echo json_encode($playlists);
