// Fonction pour afficher la popup avec un message spécifique
function showPopup(isSuccess) {
    const popupContainer = document.getElementById('popupContainer');
    const popupMessage = document.getElementById('popupMessage');

    if (!popupContainer || !popupMessage) {
        console.error("Les éléments de la popup ne sont pas trouvés !");
        return;
    }

    // Déterminer le message selon la valeur du booléen
    popupMessage.textContent = isSuccess ? "Succès : L'opération a réussi !" : "Échec : Une erreur est survenue.";

    // Afficher la popup
    popupContainer.style.display = 'block';
}



function closePop() {
    let popupContainer = document.getElementById("popupContainer");
    if (popupContainer) {
        popupContainer.style.display = "none";
    }
}