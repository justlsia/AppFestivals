<?php
require '../includes/config.php';
require '../includes/functions.php';

// Activer le rapport d'erreurs pour voir les erreurs éventuelles
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Vérifier si une requête GET avec 'q' est envoyée
if (isset($_GET['q'])) {
    $query = trim($_GET['q']);

    // Vérifier que la requête n'est pas vide
    if (!empty($query)) {
        try {
            //$stmt = $pdo->prepare("SELECT id, name FROM festivals WHERE name LIKE :query ORDER BY name LIMIT 5");
            //$stmt->bindValue(':query', "%$query%", PDO::PARAM_STR);
            //$stmt->execute();
            //$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $results = searchFestivalByName($query);

            // Définir le type de réponse en JSON
            header('Content-Type: application/json');
            echo json_encode($results);
            exit;
        } catch (PDOException $e) {
            // En cas d'erreur SQL, renvoyer un JSON avec l'erreur
            header('Content-Type: application/json');
            echo json_encode(["error" => "Erreur SQL: " . $e->getMessage()]);
            exit;
        }
    }
}

// Si aucune requête valide, renvoyer un JSON vide
header('Content-Type: application/json');
echo json_encode([]);
exit;
?>
