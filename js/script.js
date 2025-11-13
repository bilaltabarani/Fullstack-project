const testDiv = document.getElementById("test-div-js");

testDiv.addEventListener("click", function () {
    console.log("Hello World!");
});


const nav = document.getElementById("nav-js");

nav.addEventListener("click", function () {
    console.log("Nav werkt!");
});

const aanmelden_button = document.getElementById("aanmelden-button-id");

aanmelden_button.addEventListener("click", function(){
    console.log("aanmeldbutton werkt!");
})
