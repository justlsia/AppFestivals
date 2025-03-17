document.addEventListener("DOMContentLoaded", function () {
    // Confirmation avant suppression
    const deleteButtons = document.querySelectorAll(".btn-danger");
    deleteButtons.forEach(button => {
        button.addEventListener("click", function (e) {
            if (!confirm("Êtes-vous sûr de vouloir supprimer ce festival ?")) {
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




/*
* Vérifier les entrées utilisateurs à la création d'un compte
*/
function validateForm() {
    let password = document.querySelector("input[name='password']").value;
    let confirmPassword = document.querySelector("input[name='confirmPassword']").value;
    let email = document.querySelector("input[name='email']").value;
    let username = document.querySelector("input[name='username']").value;

    // Vérifier le format de l'email
    let emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailRegex.test(email)) {
        alert("Veuillez entrer une adresse e-mail valide.");
        return false;
    }

    // Vérifier le format du nom d'utilisateur
    let usernameRegex = /^[a-zA-Z0-9_]+$/;
    if (!usernameRegex.test(username)) {
        alert("Le nom d'utilisateur ne doit contenir que des lettres, chiffres et underscores.");
        return false;
    }

    // Vérifier la complexité du mot de passe
    let passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
    if (!passwordRegex.test(password)) {
        alert("Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.");
        return false;
    }

    // Vérifier si les mots de passe correspondent
    if (password !== confirmPassword) {
        alert("Les mots de passe ne correspondent pas.");
        return false;
    }

    return true;
}

