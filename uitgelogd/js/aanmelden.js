
const aanmeld_button = document.getElementById("aanmelden-button-js");

function geenGebruikersnaam(){
    let gebruikersnaam = document.getElementById("geen-gebruikersnaam");
    if(!gebruikersnaam.innerHTML){
    gebruikersnaam.innerText = "Vul uw gebruikersnaam in!";
    }
}

function geenEmail(){
    let email = document.getElementById("geen-email");
    if(!email.innerHTML){
        email.innerHTML = "Voer uw email in!";
    }
}

function geenWachtwoord(){
    let wachtwoord = document.getElementById("geen-ww");
    if(!wachtwoord.innerHTML){
        wachtwoord.innerHTML = "Voer uw wachtwoord in!";
    }
}

function geenHerhaaldWachtwoord(){
    let herhaald_wachtwoord = document.getElementById("geen-hww");
    if(!herhaald_wachtwoord.innerHTML){
        herhaald_wachtwoord.innerHTML = "Herhaal uw wachtwoord!";
    }
}

aanmeld_button.addEventListener("click", function(){
    geenGebruikersnaam();
    geenEmail();
    geenWachtwoord();
    geenHerhaaldWachtwoord();
})
