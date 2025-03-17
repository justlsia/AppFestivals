<?php
require 'config.php'; // Connexion à la base de données

/**
 * Récupère un festival par son ID
 */
function getFestivalById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM festivals WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}


/**
 * Met à jour un festival
 */
function updateFestival($id, $name, $location, $date, $description, $image) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE festivals SET name = ?, location = ?, date = ?, description = ?, image = ? WHERE id = ?");
    return $stmt->execute([$name, $location, $date, $description, $image, $id]);
}

/*
* Ajouter un utilisateur
*/
function registerUser($name, $firstname, $username, $age, $email, $password) {
    global $pdo;

    // Vérifier si le nom d'utilisateur ou l'email existe déjà
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);

    if ($stmt->fetch()) {
        return "Ce nom d'utilisateur ou cet e-mail est déjà utilisé.";
    }

    // Hashage du mot de passe
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insérer l'utilisateur dans la base de données
    $stmt = $pdo->prepare("INSERT INTO users (name, firstname, username, age, email, password) VALUES (?, ?, ?, ?, ?, ?)");

    if ($stmt->execute([$name, $firstname, $username, $age, $email, $hashedPassword])) {
        return "Compte créé avec succès !";
    } else {
        return "Erreur lors de l'inscription.";
    }
}



/*
* Connexion au site par un utilisateur
*/
function loginUser($username, $password) {
    global $pdo;

    if (empty($username) || empty($password)) {
        return "Veuillez remplir tous les champs.";
    }

    // Récupérer l'utilisateur depuis la BDD
    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si l'utilisateur existe et si le mot de passe est correct
    if ($user && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        return true; // Connexion réussie
    } else {
        return "Nom d'utilisateur ou mot de passe incorrect.";
    }
}






?>
