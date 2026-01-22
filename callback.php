<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/callback.css">
    <title>Spotify Callback</title>
</head>

<body style="font-family: Arial; margin: 40px;">

    <nav>
        <h2><a href="home.php">MusicMatch</a></h2>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="spotifylogin.html">Muziek/Playlists</a></li>
            <a href="logout.php">
                <svg width="12" height="12" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                    <path
                        d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z" />
                </svg>
                Logout
            </a>
        </ul>
    </nav>
    <main id="actions">
        <div class="main-div">
            <h1>Spotify Dashboard</h1>
            <div id="status">Loading...</div>
            <span>
                <button id="createPlaylistBtn">Create Playlist from Top 5 Tracks</button>
                <button id="showPlaylistsBtn">Show All My Playlists</button>
            </span>
        </div>

        <hr>

        <div class="playlists-display-div">
            <h3>Add a Song to a Playlist</h3>
            <input id="songInput" placeholder="Enter song name" style="padding:8px;width:300px;">
            <button onclick="searchSong()">Search</button>
            <div id="searchResults" style="margin-top:15px;"></div>
        </div>

        <div class="playlists-select-div">
            <h4>Select Playlist</h4>
            <select id="playlistSelect" style="padding:8px;width:320px;">
                <option value="">-- Choose a playlist --</option>
            </select>

            <br><br>
            <button onclick="addSelectedSong()">Add Song to Playlist</button>

    </main>

    <div id="result" style="margin-top:30px;"></div>
    </div>

    <script>
        const clientId = "86759e67500b47eea6022b1f4d9e0c02";
        const redirectUri = "http://127.0.0.1/Fullstack-project/callback.php";

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