<?php

require 'config.php'; // Connexion BDD


// ----- FESTIVALS -----

/**
 * Récupèrer un festival par son id
 */
function getFestivalById($id) {
    global $pdo;

    try {
        // Requête : Récupérer un festival selon son id
        $req = "SELECT * FROM festivals WHERE id = :id";

        $stmt = $pdo->prepare($req);
        $stmt->bindParam(':id',$id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération du festival par son id : " . $e->getMessage());
        return [];
    }
}

/**
 * Ajouter un festival
 */
function addFestival($name, $location, $date, $description, $image, $official_website) {
    global $pdo;

    try {
        // Requête : Ajouter un festival
         $req = "INSERT INTO festivals 
            (name, location, date, description, image, official_website) 
            VALUES (:name, :location, :date, :description, :official_website, :image)";
        $stmt = $pdo->prepare($req);

        // Liaison des paramètres
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':location', $location, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':official_website', $official_website, PDO::PARAM_STR);
        $stmt->bindParam(':image', $image, PDO::PARAM_STR);

        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Erreur lors de l'ajout du festival' : " . $e->getMessage());
        return [];
    }
}


/**
 * Mettre à jour un festival
 */
function updateFestival($id, $name, $location, $date, $description, $image, $official_website) {
    global $pdo;

    try {
        // Requête : Mettre à jour un festival existant
        $req = "UPDATE festivals SET 
            name = :name, location = :location, date = :date, description = :description, image = :image, official_website = :official_website 
            WHERE id = :id";

        $stmt = $pdo->prepare($req);

        // Lier les paramètres
        $stmt->bindParam(':id',$id, PDO::PARAM_INT);
        $stmt->bindParam(':name',$name, PDO::PARAM_STR);
        $stmt->bindParam(':location',$location, PDO::PARAM_STR);
        $stmt->bindParam(':date',$date, PDO::PARAM_STR);
        $stmt->bindParam(':description',$description, PDO::PARAM_STR);
        $stmt->bindParam(':image',$image, PDO::PARAM_STR);
        $stmt->bindParam(':official_website',$official_website, PDO::PARAM_STR);

        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Erreur lors de la mise à jour du festival' : " . $e->getMessage());
        return [];
    } 
}


/**
 * Supprimer un festival selon son id
 */
function deleteFestival($id) {
    global $pdo;

    try {
        // Requête : Supprimer un festival selon son id
        $req = "DELETE FROM festivals WHERE id = :id";
        $stmt = $pdo->prepare($req);

        // Lier le paramètre
        $stmt->bindParam(':id',$id, PDO::PARAM_INT);

        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Erreur lors de la suppression du festival' : " . $e->getMessage());
        return [];
    } 
}

/**
 * Supprimer un festival selon son id
 */
function searchFestivalByName($query) {
    global $pdo;

    try {
        // Requête : Supprimer un festival selon son id
        $req = "SELECT id, name 
            FROM festivals 
            WHERE name LIKE :query 
            ORDER BY name LIMIT 5";

        $stmt = $pdo->prepare($req);

        // Lier le paramètre
        $stmt->bindValue(':query', "%$query%", PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur lors de la recherche du festival' : " . $e->getMessage());
        return [];
    } 
}



// ----- USERS -----

/**
* Ajouter un utilisateur
*/
function registerUser($name, $firstname, $username, $age, $email, $password) {
    global $pdo;

    // Vérifier si le nom d'utilisateur ou l'email existe déjà
    $stmt = $pdo->prepare("SELECT id 
        FROM users 
        WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);

    if ($stmt->fetch()) {
        return "Ce nom d'utilisateur ou cet e-mail est déjà utilisé.";
    }

    // Hashage du mot de passe
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insérer l'utilisateur dans la base de données
    $stmt = $pdo->prepare("INSERT INTO users 
        (name, firstname, username, age, email, password) 
        VALUES (?, ?, ?, ?, ?, ?)");

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
    $req = "SELECT id, password 
        FROM users 
        WHERE username = :username";
    $stmt = $pdo->prepare($req);
    $stmt->bindParam(':username',$username, PDO::PARAM_STR);
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
 * Récupère un utilisateur selon son id.
 */
function getUserProfile($user_id) {
    global $pdo;

    try {
        // Requête : Récupérer un utilisateur selon son id
        $req = "SELECT * FROM users WHERE id = :id";
        $stmt = $pdo->prepare($req);

        $stmt->bindParam(':id',$user_id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération de l'utilisateur par son id' : " . $e->getMessage());
        return [];
    } 
}

/**
 * Mettre à jour le profil un l'utilisateur.
 */
function updateUserProfile($user_id, $username, $name, $firstname, $age, $email, $profile_picture) {
    global $pdo;

    try {
        // Requête : Mettre à jour un profil utilisateur
        $req = "UPDATE users SET 
            username = :username, 
            name = :name, 
            firstname = :firstname, 
            age = :age, 
            email = :email, 
            profile_picture = :profile_picture 
            WHERE id = :id";    

        $stmt = $pdo->prepare($req);

        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
        $stmt->bindParam(':age', $age, PDO::PARAM_INT);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':profile_picture', $profile_picture, PDO::PARAM_STR);

        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Erreur lors de la mise à jour de l'utilisateur' : " . $e->getMessage());
        return [];
    } 
}

/**
 * Récupérer un utilisateur selon son username 
 */
function getUserByUsername($username) {
    global $pdo;
    
    try {
        // Requête : Récupérer un utilisateur selon son username
        $req = "SELECT *
            FROM users
            WHERE username = :username";

        $stmt = $pdo->prepare($req);
        // Lier le paramètre
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération de l'utilisateur par son username' : " . $e->getMessage());
        return [];
    } 
}


/**
 * Récupérer un utilisateur selon son email 
 */
function getUserByEmail($email) {
    global $pdo;
    try {
        // Requête : Récupérer un utilisateur selon son email
        $req = "SELECT *
            FROM users
            WHERE email = :email";

        $stmt = $pdo->prepare($req);
        // Lier le paramètre
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération de l'utilisation selon son email' : " . $e->getMessage());
        return [];
    } 
}


/**
* Récupérer un utilisateur selon son email ou google_id
*/
function getUserByEmailOrGoogleId($email, $google_id) {
    global $pdo;
    try {
        // Requête : Ajouter un nouveau token google à un utilisateur
        $req = "SELECT * 
            FROM users 
            WHERE google_id = :google_id 
            OR email = :email";

        $stmt = $pdo->prepare($req);

        // Lier le paramètre
        $stmt->bindParam(':google_id',$google_id, PDO::PARAM_STR);
        $stmt->bindParam(':email',$email, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur lors de la recherche de l'utilisateur par son email ou google id' : " . $e->getMessage());
        return [];
    } 
}


/**
* Mettre à jour un google_id d'un utilisateur selon son email 
*/
function UpdateGoogleIdByEmail($google_id, $email) {
    global $pdo;
    try {
        // Requête : Ajouter un nouveau token google à un utilisateur
        $req = "UPDATE users SET 
            google_id = :google_id 
            WHERE email = :email";

        $stmt = $pdo->prepare($req);

        // Lier le paramètre
        $stmt->bindParam(':google_id',$google_id, PDO::PARAM_STR);
        $stmt->bindParam(':email',$email, PDO::PARAM_STR);
        
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Erreur lors de la mise à jour du google_id de l'utilisateur par son email ' : " . $e->getMessage());
        return [];
    } 
}

/**
* Ajouter un utilisateur google 
*/
function addUserByGoogleAuth($google_id, $email, $username, $name, $firstname) {
    global $pdo;
    try {
        // Requête : Ajouter un nouveau token google à un utilisateur
        $req = "INSERT INTO users (google_id, email, username, name, firstname, age) 
        VALUES (:google_id, :email, :username, :name, :firstname, 0)";

        $stmt = $pdo->prepare($req);

        // Lier le paramètre
        $stmt->bindParam(':google_id',$google_id, PDO::PARAM_STR);
        $stmt->bindParam(':email',$email, PDO::PARAM_STR);
        $stmt->bindParam(':username',$username, PDO::PARAM_STR);
        $stmt->bindParam(':name',$name, PDO::PARAM_STR);
        $stmt->bindParam(':firstname',$firstname, PDO::PARAM_STR);
        
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Erreur lors de l'ajout d'un utilisateur avec Google ' : " . $e->getMessage());
        return [];
    } 
}




// ----- GOOGLE AUTENTICATION -----

/**
* Supprimer l'ancien token de connexion
*/
function deleteOldTokenGoogle($email) {
    global $pdo;
    try {
        // Requête : Supprimer l'ancien token de connexion google d'un utilisateur selon son email
        $req = "DELETE FROM password_resets WHERE email = :email";
        $stmt = $pdo->prepare($req);

        // Lier le paramètre
        $stmt->bindParam(':email',$email, PDO::PARAM_STR);

        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Erreur lors de la suppression de l'ancien token google' : " . $e->getMessage());
        return [];
    } 
    

}

/**
* Ajouter un nouveau token de connexion
*/
function addNewTokenGoogle($email, $token, $expires) {
    global $pdo;
    try {
        // Requête : Ajouter un nouveau token google à un utilisateur
        $req = "INSERT INTO password_resets (email, token, expires) VALUES (:email, :token, :expires)";
        $stmt = $pdo->prepare($req);

        // Lier le paramètre
        $stmt->bindParam(':email',$email, PDO::PARAM_STR);
        $stmt->bindParam(':token',$token);
        $stmt->bindParam(':expires',$expires, PDO::PARAM_STR);

        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Erreur lors de l'ajout du token google' : " . $e->getMessage());
        return [];
    } 
}








?>