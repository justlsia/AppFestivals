<?php

require 'config.php'; // Connexion BDD


// ----- FESTIVALS -----

/**
 * Récupèrer un festival par son ID
 */
function getFestivalById($id) {
    global $pdo;

    // Requête : Récupérer un festival selon son id
    $req = "SELECT * FROM festivals WHERE id = :id";

    $stmt = $pdo->prepare($req);
    $stmt->bindParam(':id',$id);
    $stmt->execute();

    return $stmt->fetch();
}

/**
 * Ajouter un festival
 */
function addFestival($name, $location, $date, $description, $image, $official_website) {
    global $pdo;

    // Requête : Ajouter un festival
    $req = "INSERT INTO festivals (name, location, date, description, image, official_website) VALUES (:name, :location, :date, :description, :official_website, :image)";
    $stmt = $pdo->prepare($req);

    // Liaison des paramètres
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':location', $location);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':official_website', $official_website);
    $stmt->bindParam(':image', $image);

    return $stmt->execute();

}


/**
 * Mettre à jour un festival
 */
function updateFestival($id, $name, $location, $date, $description, $image, $official_website) {
    global $pdo;

    // Requête : Mettre à jour un festival existant
    $req = "UPDATE festivals SET name = :name, location = :location, date = :date, description = :description, image = :image, official_website = :official_website WHERE id = :id";

    $stmt = $pdo->prepare($req);

    // Lier les paramètres
    $stmt->bindParam(':id',$id);
    $stmt->bindParam(':name',$name);
    $stmt->bindParam(':location',$location);
    $stmt->bindParam(':date',$date);
    $stmt->bindParam(':description',$description);
    $stmt->bindParam(':image',$image);
    $stmt->bindParam(':official_website',$official_website);

    return $stmt->execute();
}


function deleteFestival($id) {
    global $pdo;

    // Requête : Supprimer un festival selon son id
    $req = "DELETE FROM festivals WHERE id = :id";
    $stmt = $pdo->prepare($req);

    // Lier le paramètre
    $stmt->bindParam(':id',$id);

    return $stmt->execute();
}


// ----- USERS -----

/**
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


/**
* Connexion utilisateur
*/
function loginUser($username, $password) {
    global $pdo;

    if (empty($username) || empty($password)) {
        return "Veuillez remplir tous les champs.";
    }

    // Requête : Récupérer les données de connexion d'un utilisateur selon son username
    $req = "SELECT id, password FROM users WHERE username = :username";
    $stmt = $pdo->prepare($req);
    $stmt->bindParam(':username',$username);
    $stmt->execute();
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


/**
 * Récupère les informations de l'utilisateur connecté.
 */
function getUserProfile($user_id) {
    global $pdo;
    //$stmt = $pdo->prepare("SELECT username, name, firstname, age, email, profile_picture FROM users WHERE id = ?");
    //$stmt->execute([$user_id]);

    // Requête : Récupérer un utilisateur selon son id
    $req = "SELECT * FROM users WHERE id = :id";
    $stmt = $pdo->prepare($req);

    $stmt->bindParam(':id',$user_id);

    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Mettre à jour le profil un l'utilisateur.
 */
function updateUserProfile($user_id, $name, $firstname, $age, $email, $profile_picture) {
    global $pdo;

    // Requête : Mettre à jour un profil utilisateur
    $req = "UPDATE users SET name = :name, firstname = :firstname, age = :age, email = :email, profile_picture = :profile_picture WHERE id = :id";
    $stmt = $pdo->prepare($req);

    $stmt->bindParam(':id',$user_id);
    $stmt->bindParam(':id',$name);
    $stmt->bindParam(':id',$firstname);
    $stmt->bindParam(':id',$age);
    $stmt->bindParam(':id',$email);
    $stmt->bindParam(':id',$profile_picture);

    $stmt->execute();

}



?>
