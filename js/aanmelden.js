const username_field = document.getElementById("username-field-js");
const email_field = document.getElementById("email-field-js");
const password1_field = document.getElementById("pw1-field-js");
const password2_field = document.getElementById("pw2-field-js");
const aanmeld_button = document.getElementById("aanmelden-button-js");


username_field.addEventListener("click", function(){
    console.log("username field werkt!");
});

email_field.addEventListener("click", function(){
    console.log("email field werkt!");
});

aanmeld_button.addEventListener("click", function(){
    console.log("aanmeldbutton werkt!");
});

password1_field.addEventListener("click", function(){
    console.log("wachtwoord field werkt!");
});

password2_field.addEventListener("click", function(){
    console.log("wachtwoord herhaal field werkt!");
});
