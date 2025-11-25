const inloggen_button = document.getElementById("inloggen-button-js");

inloggen_button.addEventListener("click", function () {
    console.log("inlogknop werkt!");
    window.location.href='/ingelogd/html/home-ingelogd.html';
})

