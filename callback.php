<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Spotify Callback</title>
</head>

<body style="font-family: Arial; margin: 40px;">

<h1>Spotify Dashboard</h1>
<div id="status">Loading...</div>

<div id="actions" style="display:none;">

    <button id="createPlaylistBtn">Create Playlist from Top 5 Tracks</button>
    <button id="showPlaylistsBtn">Show All My Playlists</button>

    <hr>

    <h3>Add a Song to a Playlist</h3>

    <input id="songInput" placeholder="Enter song name" style="padding:8px;width:300px;">
    <button onclick="searchSong()">Search</button>

    <div id="searchResults" style="margin-top:15px;"></div>

    <h4>Select Playlist</h4>
    <select id="playlistSelect" style="padding:8px;width:320px;">
        <option value="">-- Choose a playlist --</option>
    </select>

    <br><br>
    <button onclick="addSelectedSong()">Add Song to Playlist</button>

</div>

<div id="result" style="margin-top:30px;"></div>

<script>
 const clientId = "f7e11fa93c254442b9e46b69b406b838";
 const redirectUri = "http://127.0.0.1/fullstack-project/Fullstack-project/callback.php";

let accessToken = null;
let selectedTrackUri = null;

const params = new URLSearchParams(window.location.search);
const code = params.get("code");

if (code) exchangeToken(code);

async function exchangeToken(code) {
    const body = new URLSearchParams({
        client_id: clientId,
        grant_type: "authorization_code",
        code,
        redirect_uri: redirectUri,
        code_verifier: localStorage.getItem("code_verifier")
    });

    const res = await fetch("https://accounts.spotify.com/api/token", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body
    });

    const data = await res.json();
    accessToken = data.access_token;

    document.getElementById("status").innerText = "Logged in successfully ✅";
    document.getElementById("actions").style.display = "block";

    loadPlaylistDropdown();
}

document.getElementById("createPlaylistBtn").onclick = async () => {
    const res = await fetch("createplaylist.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ token: accessToken })
    });

    const data = await res.json();
    document.getElementById("result").innerHTML =
        data.error ? data.error : `<b>Playlist created:</b> ${data.playlist.name}`;
};

document.getElementById("showPlaylistsBtn").onclick = async () => {
    const res = await fetch("getplaylists.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ token: accessToken })
    });

    const data = await res.json();
    let html = "<h2>Your Playlists</h2><ul>";

    data.items.forEach(pl => {
        html += `<li><b>${pl.name}</b><ul>`;
        pl.tracks_list.forEach(t => {
            html += `<li>${t.name} – ${t.artists.join(", ")}</li>`;
        });
        html += "</ul></li>";
    });

    html += "</ul>";
    document.getElementById("result").innerHTML = html;
};

async function searchSong() {
    const query = document.getElementById("songInput").value;

    const res = await fetch("search_track.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ token: accessToken, query })
    });

    const tracks = await res.json();
    let html = "<ul>";

    tracks.forEach(t => {
        html += `
            <li>
                ${t.name} – ${t.artists.join(", ")}
                <button onclick="selectTrack('${t.uri}')">Select</button>
            </li>`;
    });

    html += "</ul>";
    document.getElementById("searchResults").innerHTML = html;
}

function selectTrack(uri) {
    selectedTrackUri = uri;
    alert("Song selected! Now choose a playlist.");
}

async function loadPlaylistDropdown() {
    const res = await fetch("getplaylists.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ token: accessToken })
    });

    const data = await res.json();
    const select = document.getElementById("playlistSelect");

    select.innerHTML = `<option value="">-- Choose a playlist --</option>`;

    data.items.forEach(pl => {
        const opt = document.createElement("option");
        opt.value = pl.id;
        opt.textContent = pl.name;
        select.appendChild(opt);
    });
}

async function addSelectedSong() {
    const playlistId = document.getElementById("playlistSelect").value;

    if (!selectedTrackUri) {
        alert("Select a song first!");
        return;
    }

    if (!playlistId) {
        alert("Select a playlist!");
        return;
    }

    const res = await fetch("add_to_playlist.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            token: accessToken,
            playlist_id: playlistId,
            track_uri: selectedTrackUri
        })
    });

    const data = await res.json();
    alert(data.success ? "Song added 🎶" : "Failed to add song");
}
</script>

</body>
</html>
