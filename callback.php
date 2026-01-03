<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Spotify Callback</title>
</head>
<body style="font-family: Arial; margin: 40px;">
    <h1>Spotify Options</h1>
    <div id="status">Loading...</div>

    <div id="actions" style="margin-top:20px; display:none;">
        <button id="createPlaylistBtn" style="padding:10px 18px; font-size:16px; margin-right:15px;">
            Create Playlist from Top 5 Tracks
        </button>

        <button id="showPlaylistsBtn" style="padding:10px 18px; font-size:16px;">
            Show All My Playlists
        </button>
    </div>

    <div id="result" style="margin-top:30px;"></div>

    <script>
        const clientId = "f7e11fa93c254442b9e46b69b406b838";
        const redirectUri = "http://127.0.0.1/fullstack-project/Fullstack-project/callback.php";

        const params = new URLSearchParams(window.location.search);
        const code = params.get("code");

        if (!code) {
            document.getElementById("status").innerHTML =
                "<b>Error:</b> No authorization code found!";
        } else {
            exchangeToken(code);
        }

        let accessToken = null;

        async function exchangeToken(code) {
            const codeVerifier = localStorage.getItem("code_verifier");

            const body = new URLSearchParams({
                client_id: clientId,
                grant_type: "authorization_code",
                code: code,
                redirect_uri: redirectUri,
                code_verifier: codeVerifier
            });

            const result = await fetch("https://accounts.spotify.com/api/token", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body
            });

            const data = await result.json();

            if (!data.access_token) {
                document.getElementById("status").innerHTML =
                    "<b>Token Error:</b> " + JSON.stringify(data);
                return;
            }

            accessToken = data.access_token;

            document.getElementById("status").innerHTML = "Logged in successfully!";
            document.getElementById("actions").style.display = "block";
        }

        document.getElementById("createPlaylistBtn").onclick = async () => {
            document.getElementById("result").innerHTML = "Creating playlist...";
            const response = await fetch("createplaylist.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ token: accessToken })
            });

            const data = await response.json();

            if (data.error) {
                document.getElementById("result").innerHTML = "<b>Error:</b> " + data.error;
                return;
            }

            document.getElementById("result").innerHTML = `
                <h2>Playlist Created 🎉</h2>
                <p><b>Name:</b> ${data.playlist.name}</p>
                <p><b>Playlist ID:</b> ${data.playlist.id}</p>
                <p>You can find the playlist on your account, or see it in the list below!</p>
            `;
        };

        document.getElementById("showPlaylistsBtn").onclick = async () => {
            document.getElementById("result").innerHTML = "Loading playlists...";
            const response = await fetch("getplaylists.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ token: accessToken })
            });

            const data = await response.json();
            if (data.error) {
                document.getElementById("result").innerHTML = "<b>Error:</b> " + data.error;
                return;
            }

            let html = "<h2>Your Playlists</h2><ul>";
            data.items.forEach(pl => {
                html += `
                    <li>
                        <b>${pl.name}</b> (${pl.tracks.total} tracks)
                        <br>
                        <button onclick="likePlaylist('${pl.id}')">❤️ Like</button>
                        <button onclick="addComment('${pl.id}')">💬 Comment</button>
                        <div id="social-${pl.id}">Loading...</div>
                        <ul>
                `;
                if (pl.tracks_list) {
                    pl.tracks_list.forEach(track => {
                        html += `<li>${track.name} by ${track.artists.join(", ")}</li>`;
                    });
                }
                html += "</ul></li>";

                loadSocial(pl.id);
            });
            html += "</ul>";

            document.getElementById("result").innerHTML = html;
        };

        async function likePlaylist(playlistId) {
            const res = await fetch("like_playlist.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ playlist_id: playlistId }),
                credentials: "same-origin"
            });

            const data = await res.json();
            alert(data.message);
            loadSocial(playlistId);
        }

        async function addComment(playlistId) {
            const comment = prompt("Write a comment");
            if (!comment) return;

            await fetch("comment_playlist.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ playlist_id: playlistId, comment }),
                credentials: "same-origin"
            });

            loadSocial(playlistId);
        }

        async function loadSocial(playlistId) {
            const res = await fetch("get_social.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ playlist_id: playlistId }),
                credentials: "same-origin"
            });

            const data = await res.json();
            let html = `<p>❤️ ${data.likes} likes</p><ul>`;
            data.comments.forEach(c => {
                html += `<li><b>${c.username}</b>: ${c.comment}</li>`;
            });
            html += "</ul>";
            document.getElementById("social-" + playlistId).innerHTML = html;
        }
    </script>
</body>
</html>
