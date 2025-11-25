const playlist_afspelen = document.getElementById("playlist-afspelen");
const playlist_maken = document.getElementById("playlist-maken");
const muziek_afspelen = document.getElementById("muziek-afspelen");
const muziek_maken = document.getElementById("muziek-maken");

function naarPlaylistToe(){
    console.log("button werkt");
    window.location.href = "/ingelogd/html/playlists.html";
}

function naarMuziekToe(){
    console.log("button werkt");
    window.location.href = "/ingelogd/html/muziek.html";
}

playlist_afspelen.addEventListener("click", function(){
    naarPlaylistToe();
})

playlist_maken.addEventListener("click", function(){
    naarPlaylistToe();
})

muziek_afspelen.addEventListener("click", function(){
    naarMuziekToe();
})

muziek_maken.addEventListener("click", function(){
    naarMuziekToe();
})
