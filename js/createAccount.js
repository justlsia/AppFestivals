/*
* Vérifier le mot de passe utilisateur à la création d'un compte
*/
alert("Le script est exécuté !");
console.log("Le script est exécuté !");


document.addEventListener("DOMContentLoaded", function() {

    const form = document.getElementById("registerForm");

    if (!form) {
        console.error("❌ Erreur : Formulaire introuvable !");
        return;
    }

    form.addEventListener("submit", function(event) {
        console.log("Événement submit déclenché");

        const password = document.querySelector("input[name='password']").value;
        alert("Mot de passe saisi :" + password);
        const confirmPassword = document.querySelector("input[name='confirmPassword']").value;

        // Vérifier si les mots de passe correspondent
        if (password !== confirmPassword) {
            alert("Les mots de passe ne correspondent pas ! ❌");
            event.preventDefault(); // Empêcher l'envoi du formulaire
            return;
        }

        // Vérifier la longueur du mot de passe
        if (password.length < 8) {
            alert("Le mot de passe doit contenir au moins 8 caractères ! ❌");
            event.preventDefault();
            return;
        }

        // Vérifier si le mot de passe contient au moins une lettre, un chiffre et un caractère spécial
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{8,}$/;

        if (!regex.test(password)) {
            console.log("Mot de passe invalide ! ❌");
            alert("Le mot de passe doit contenir au moins une lettre, un chiffre et un caractère spécial ! ❌");
            event.preventDefault();
            return;
        }

        console.log("Formulaire valide, envoi en cours.");
    });
});



