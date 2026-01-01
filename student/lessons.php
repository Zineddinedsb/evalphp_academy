<?php
require_once "../config/database.php";
require_once "../includes/auth_check.php";

/* Vérification rôle */
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit;
}

// Récupérer toutes les leçons
$stmt = $pdo->query("SELECT * FROM lessons ORDER BY part, id ASC");
$lessonsRaw = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Supprimer les doublons basés sur title + description
$lessonsUnique = [];
foreach ($lessonsRaw as $l) {
    $key = $l['part'].'|'.$l['title'].'|'.$l['description'];
    if (!isset($lessonsUnique[$key])) {
        $lessonsUnique[$key] = $l;
    }
}
$lessons = array_values($lessonsUnique);

$currentPart = 0;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Table des matières | EvalPHP Academy</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-dark bg-primary px-4">
    <span class="navbar-brand">🎓 EvalPHP Academy</span>
    <a href="dashboard.php" class="btn btn-light btn-sm me-2">Dashboard</a>
    <a href="../auth/logout.php" class="btn btn-light btn-sm">Déconnexion</a>
</nav>

<div class="container mt-5 mb-5">
    <h2>📚 Table des matières</h2>

    <?php foreach ($lessons as $l): ?>
        <?php if ($currentPart !== $l['part']): ?>
            <?php $currentPart = $l['part']; ?>
            <h3 class="mt-4">Partie <?= $currentPart ?></h3>
            <hr>
        <?php endif; ?>

        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <h5><?= htmlspecialchars($l['title']) ?></h5>
                <p><?= htmlspecialchars($l['description']) ?></p>

                <?php if (stripos($l['title'], 'Quiz') !== false): ?>
                    <a href="questions.php?part=<?= $l['part'] ?>" class="btn btn-success btn-sm">📝 Commencer le QCM</a>
                <?php else: ?>
                    <a href="lesson_view.php?id=<?= $l['id'] ?>" class="btn btn-primary btn-sm">📖 Découvrir la leçon</a>
                <?php endif; ?>
            </div>
        </div>

    <?php endforeach; ?>
</div>

<!-- Footer -->
<footer class="bg-light text-center py-3 mt-5 shadow-sm">
    &copy; <?= date('Y') ?> EvalPHP Academy. Tous droits réservés.
</footer>

</body>
</html>
