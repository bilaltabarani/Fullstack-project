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
    $response = file_get_contents($url, false, stream_context_create($opts));
    if (!$response) return null;
    return json_decode($response, true);
}

function fetchAll($url, $token) {
    $allItems = [];
    while ($url) {
        $data = spotifyGET($url, $token);
        if (!$data) break;
        if (isset($data['items'])) {
            $allItems = array_merge($allItems, $data['items']);
        }
        $url = $data['next'] ?? null;
    }
    return $allItems;
}

$playlistsData = spotifyGET("https://api.spotify.com/v1/me/playlists?limit=50", $token);
if (!$playlistsData) die(json_encode(["error" => "Failed to fetch playlists"]));

$playlists = $playlistsData['items'];

foreach ($playlists as &$playlist) {
    $playlistId = $playlist['id'];
    $tracksItems = fetchAll("https://api.spotify.com/v1/playlists/$playlistId/tracks?limit=50", $token);
    
    $playlist['tracks_list'] = [];
    foreach ($tracksItems as $item) {
        if (isset($item['track'])) {
            $track = $item['track'];
            $playlist['tracks_list'][] = [
                "name" => $track['name'],
                "artists" => array_map(fn($a) => $a['name'], $track['artists']),
                "id" => $track['id'],
                "uri" => $track['uri']
            ];
        }
    }
}

echo json_encode([
    "items" => $playlists
]);
