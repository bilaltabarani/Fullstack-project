const luister_button = document.getElementById("luisteren-id");
const inlog_button = document.getElementById("inloggen-id");
const aanmeld_button = document.getElementById("aanmelden-id");

luister_button.addEventListener("click", function(){
    window.location.href = "muziek-luisteren.html";
});

inlog_button.addEventListener("click", function(){
    window.location.href = "inloggen.html";
});

aanmeld_button.addEventListener("click", function(){
    window.location.href = "aanmelden.html";
})
