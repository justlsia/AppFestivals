<?php
require '../includes/config.php';

// Vérifier la présence de l’ID du festival
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: festivals.php");
    exit();
}

$festival_id = (int) $_GET['id'];

// Récupérer le nom du festival
$stmt = $pdo->prepare("SELECT name FROM festivals WHERE id = :id");
$stmt->execute(['id' => $festival_id]);
$festival = $stmt->fetch();

if (!$festival) {
    echo "<p>Festival introuvable.</p>";
    exit();
}

// Récupérer les commentaires
$stmt = $pdo->prepare("SELECT info.note, info.commentaire, info.date_posted, u.username
                       FROM infofestival AS info
                       JOIN users AS u ON info.user_id = u.id
                       WHERE info.festival_id = :id
                       ORDER BY info.date_posted DESC");
$stmt->execute(['id' => $festival_id]);
$commentaires = $stmt->fetchAll();




// Traitement du formulaire d'ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_commentaire'])) {
    if (isset($_SESSION['user'])) {
        $note = (int) $_POST['note'];
        $commentaire = trim($_POST['commentaire']);
        $user_id = $_SESSION['user']['id'];

        if ($note >= 1 && $note <= 5 && !empty($commentaire)) {
            $stmt = $pdo->prepare("INSERT INTO infofestival (festival_id, user_id, note, commentaire, date_posted)
                                   VALUES (:festival_id, :user_id, :note, :commentaire, NOW())");
            $stmt->execute([
                'festival_id' => $festival_id,
                'user_id' => $user_id,
                'note' => $note,
                'commentaire' => $commentaire
            ]);

            // Redirection pour éviter le re-post
            header("Location: commentaire.php?id=" . $festival_id);
            exit();
        }
    }
}



?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Commentaires - <?= htmlspecialchars($festival['name']) ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Commentaires sur le festival : <?= htmlspecialchars($festival['name']) ?></h2>
    <a href="festivals.php" class="btn btn-primary mb-3">Retour à la liste</a>

    <?php if (count($commentaires) > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Note</th>
                    <th>Commentaire</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($commentaires as $comm): ?>
                    <tr>
                        <td><?= htmlspecialchars($comm['username']) ?></td>
                        <td><?= (int) $comm['note'] ?>/5</td>
                        <td><?= nl2br(htmlspecialchars($comm['commentaire'])) ?></td>
                        <td><?= date("d/m/Y H:i", strtotime($comm['date_posted'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucun commentaire pour ce festival.</p>
    <?php endif; ?>

    <hr>
    <h4>Ajouter un commentaire</h4>

    <?php if (!isset($_SESSION)) session_start(); ?>
    <?php if (!isset($_SESSION['user'])): ?>
        <p>Vous devez être <a href="login.php">connecté</a> pour commenter.</p>
    <?php else: ?>
        <form action="commentaire.php?id=<?= $festival_id ?>" method="post">
            <div class="mb-3">
                <label for="note" class="form-label">Note (/5)</label>
                <select name="note" id="note" class="form-select" required>
                    <option value="">Choisir une note</option>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="commentaire" class="form-label">Commentaire</label>
                <textarea name="commentaire" id="commentaire" rows="4" class="form-control" required></textarea>
            </div>

            <button type="submit" name="submit_commentaire" class="btn btn-success">Publier</button>
        </form>
    <?php endif; ?>


</div>

</body>
</html>
