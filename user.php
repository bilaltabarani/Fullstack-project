<?php
session_start();
if (!isset($_SESSION['account_loggedin'])) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/user.css">
    <title>User</title>
</head>

<body>
    <nav id="nav-js">
        <h2><a href="home.php">MusicMatch</a></h2>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="spotifylogin.html">Muziek afspelen</a></li>
            <li><a href="spotifylogin.html">Playlist aanmaken</a></li>
            <li><a href="user.php">Mijn account</a></li>
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
    <div class="user-div">
        <div>
            <p>Hier krijgt de gebruiker informatie over hun account te zien.</p>
            <button>Veranderen</button>
        </div>
        <div>
            <p>Hier krijgt de gebruiker hun gebruikersnaam te zien en de optie om hun gebruikersnaam aan te passen.</p>
            <button>Veranderen</button>
        </div>
        <div>
            <p>Hier krijgt de gebruiker hun email te zien en de optie om deze aan te passen</p>
            <button>Veranderen</button>
        </div>
        <div>
            <section>
                <p>Hier krijgt de gebruiker hun top 5 tracks te zien en de optie om naar ze te luisteren.</p>
                <button>Luisteren</button>
            </section>
            <section>
                <p>Hier krijgt de gebruiker 5 van hun playlists te zien en de optie om ze af te spelen.</p>
                <button>Afspelen</button>
            </section>
        </div>
    </div>
</body>

</html>