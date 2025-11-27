const luister_button = document.getElementById("luisteren-id");
const inlog_button = document.getElementById("inloggen-id");
const aanmeld_button = document.getElementById("aanmelden-id");

function naarMuziekLuisteren() {
    window.location.href = "/ingelogd/html/muziek.html";

}

function naarInloggen() {
    window.location.href = "/uitgelogd/html/inloggen.html";

}

function naarAanmelden() {
    window.location.href = "/uitgelogd/html/aanmelden.html";

}

luister_button.addEventListener("click", function () {
    naarMuziekLuisteren();
});

inlog_button.addEventListener("click", function () {
   naarInloggen();
});

aanmeld_button.addEventListener("click", function () {
    naarAanmelden();
})
