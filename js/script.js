document.addEventListener("DOMContentLoaded", function () {
    // Confirmation avant suppression
    const deleteButtons = document.querySelectorAll(".btn-danger");
    deleteButtons.forEach(button => {
        button.addEventListener("click", function (e) {
            if (!confirm("Êtes-vous sûr de vouloir supprimer cet élément ?")) {
                e.preventDefault();
            }
        });
    });

    // Animation d'affichage
    document.querySelectorAll("h2").forEach(title => {
        title.style.opacity = 0;
        setTimeout(() => {
            title.style.transition = "opacity 1s ease-in-out";
            title.style.opacity = 1;
        }, 200);
    });
});


/*
Rechercher un festival par son nom
*/
function initSearch() {
    let searchInput = document.getElementById("searchInput");
    let suggestions = document.getElementById("suggestions");

    if (!searchInput || !suggestions) {
        console.error("Élément introuvable !");
        return;
    }

    searchInput.addEventListener("keyup", function () {
        let query = searchInput.value.trim();

        if (query.length > 1) {
            fetch("../actions/search_festival.php?q=" + encodeURIComponent(query))
                .then(response => response.json())
                .then(data => {
                    suggestions.innerHTML = "";
                    if (data.length > 0) {
                        data.forEach(festival => {
                            let item = document.createElement("a");
                            item.href = "../pages/detail.php?id=" + festival.id;
                            item.classList.add("list-group-item", "list-group-item-action");
                            item.textContent = festival.name;
                            suggestions.appendChild(item);
                        });
                    } else {
                        let item = document.createElement("div");
                        item.classList.add("list-group-item", "text-muted");
                        item.textContent = "Aucun résultat trouvé";
                        suggestions.appendChild(item);
                    }
                })
                .catch(error => console.error("Erreur lors de la recherche :", error));
        } else {
            suggestions.innerHTML = "";
        }
    });

    document.addEventListener("click", function (event) {
        if (!searchInput.contains(event.target) && !suggestions.contains(event.target)) {
            suggestions.innerHTML = "";
        }
    });
}








/*
* Ouvrir une popup
*/ 
function openPopup() {
    let modal = document.getElementById("customModal");
    if (modal) {
        modal.style.display = "flex";
    }
}

/*
* Fermer une popup
*/ 
function closePopup() {
    let modal = document.getElementById("customModal");
    if (modal) {
        modal.style.display = "none";
    }
}





