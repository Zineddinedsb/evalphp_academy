<?php
require_once "../config/database.php";
require_once "../includes/auth_check.php";

/* Vérification rôle */
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit;
}

// Vérifier qu'un id de leçon est fourni
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: lessons.php");
    exit;
}

$lessonId = (int) $_GET['id'];

// Récupérer la leçon
$stmt = $pdo->prepare("SELECT * FROM lessons WHERE id = ?");
$stmt->execute([$lessonId]);
$lesson = $stmt->fetch();

if (!$lesson) {
    die("Leçon non trouvée.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($lesson['title']) ?> | EvalPHP Academy</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
<style>
.back-btn {
    font-size: 1rem;
    margin-bottom: 20px;
}
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-dark bg-primary px-4">
    <span class="navbar-brand">🎓 EvalPHP Academy</span>
    <a href="dashboard.php" class="btn btn-light btn-sm me-2">Dashboard</a>
    <a href="../auth/logout.php" class="btn btn-light btn-sm">Déconnexion</a>
</nav>

<div class="container mt-4 mb-5">

    <!-- Bouton Retour -->
    <a href="lessons.php" class="btn btn-outline-secondary back-btn">← Retour à la Table des matières</a>

    <!-- Contenu de la leçon -->
    <div class="card shadow-sm">
        <div class="card-body">
            <h3><?= htmlspecialchars($lesson['title']) ?></h3>
            <hr>
            <p><?= nl2br(htmlspecialchars($lesson['description'])) ?></p>
        </div>
    </div>

</div>

<!-- Footer -->
<footer class="bg-light text-center py-3 mt-5 shadow-sm">
    &copy; <?= date('Y') ?> EvalPHP Academy. Tous droits réservés.
</footer>

</body>
</html>
