document.addEventListener("DOMContentLoaded", function() {
    // Récupérer le formulaire de connexion
    var loginForm = document.getElementById("login-form");

    // Ajouter un écouteur d'événement sur le formulaire
    loginForm.addEventListener("submit", function(event) {
        event.preventDefault(); // Empêcher le comportement par défaut du formulaire

        // Récupérer les valeurs des champs
        var emailField = document.getElementById("email");
        var passwordField = document.getElementById("password");
        var email = emailField.value;
        var password = passwordField.value;

        // Effectuer une requête AJAX pour vérifier les identifiants
        performLoginRequest(email, password);
    });
});

function performLoginRequest(email, password) {
    // Créer un objet XMLHttpRequest
    var xhr = new XMLHttpRequest();

    // Configurer la requête HTTP
    xhr.open("POST", "login.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // Définir un gestionnaire d'événement pour la réponse de la requête
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            // Traiter la réponse du serveur
            handleLoginResponse(xhr.responseText);
        }
    };

    // Envoyer la requête avec les données du formulaire
    xhr.send("email=" + encodeURIComponent(email) + "&password=" + encodeURIComponent(password));
}

function handleLoginResponse(response) {
    // Traiter la réponse du serveur
    if (response === "success") {
        // Rediriger l'utilisateur vers la page de profil
        window.location.href = "profile.php";
    } else {
        // Afficher un message d'erreur
        alert("Identifiants incorrects.");
    }
}