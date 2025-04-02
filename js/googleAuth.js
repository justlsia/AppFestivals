/*
* Authentification Google
*/
// Fonction pour gérer la connexion via Google
function handleCredentialResponse(response) {
    console.log("Token Google reçu:", response.credential); // Vérifie si le token est bien récupéré

    fetch('../actions/process_google_login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'credential=' + encodeURIComponent(response.credential)
        })
        .then(res => res.text()) // 🔍 Change `json()` en `text()` pour voir la réponse brute
        .then(data => {
            console.log("Réponse brute du serveur:", data); // Vérifie le retour du PHP

            try {
                let json = JSON.parse(data); // Tente de convertir en JSON
                if (json.success) {
                    console.log("Connexion réussie, redirection...");
                    window.location.href = "../pages/festivals.php"; // Redirige après connexion
                } else {
                    console.error("Erreur de connexion:", json.message);
                    alert("Échec de la connexion avec Google: " + json.message);
                }
            } catch (e) {
                console.error("Erreur JSON:", e, data); // Si le JSON est invalide, affiche l'erreur
                alert("Problème avec la réponse du serveur. Vérifie la console.");
            }
        })
        .catch(error => console.error("Erreur réseau:", error));
}