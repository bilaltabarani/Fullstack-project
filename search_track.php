<?php
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$token = $data["token"] ?? null;
$query = $data["query"] ?? null;

if (!$token || !$query) {
    echo json_encode(["error" => "Missing token or query"]);
    exit;
}

$url = "https://api.spotify.com/v1/search?q=" . urlencode($query) . "&type=track&limit=5";

$opts = [
    "http" => [
        "method" => "GET",
        "header" => "Authorization: Bearer $token"
    ]
];

$response = file_get_contents($url, false, stream_context_create($opts));
$data = json_decode($response, true);

$tracks = [];
foreach ($data["tracks"]["items"] as $track) {
    $tracks[] = [
        "name" => $track["name"],
        "artists" => array_map(fn($a) => $a["name"], $track["artists"]),
        "uri" => $track["uri"]
    ];
}

echo json_encode($tracks);
