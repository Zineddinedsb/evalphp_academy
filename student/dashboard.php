<?php
require_once "../config/database.php";
require_once "../includes/auth_check.php";

/* Vérification rôle */
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit;
}

$userId = (int) $_SESSION['user']['id'];

/* Total questions */
try {
    $stmtTotal = $pdo->query("SELECT COUNT(*) FROM questions");
    $totalQuestions = (int) $stmtTotal->fetchColumn();
} catch (Exception $e) {
    $totalQuestions = 0;
}

/* Questions répondues */
$stmt = $pdo->prepare("SELECT COUNT(*) FROM answers WHERE user_id = ?");
$stmt->execute([$userId]);
$answeredQuestions = (int) $stmt->fetchColumn();

/* Calcul progression */
$progress = ($totalQuestions > 0) ? round(($answeredQuestions / $totalQuestions) * 100) : 0;
$progressCSS = min(100, max(0, $progress));

/* Détermination du niveau */
if ($progress < 25) {
    $niveauLabel = 'Débutant 🟢';
    $niveauColor = 'success';
} elseif ($progress < 50) {
    $niveauLabel = 'Intermédiaire 🔵';
    $niveauColor = 'primary';
} elseif ($progress < 75) {
    $niveauLabel = 'Avancé 🟡';
    $niveauColor = 'warning';
} else {
    $niveauLabel = 'Expert 🏆';
    $niveauColor = 'dark';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Dashboard Étudiant | EvalPHP Academy</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

<nav class="navbar navbar-dark bg-primary px-4">
    <span class="navbar-brand">🎓 EvalPHP Academy</span>
    <a href="../auth/logout.php" class="btn btn-light btn-sm">Déconnexion</a>
</nav>

<div class="container mt-5">

    <!-- Carte principale -->
    <div class="card shadow border-0 mb-4">
        <div class="card-body">
            <div class="row align-items-center">

                <div class="col-md-8">
                    <h3>Évaluation PHP & MySQL</h3>
                    <p class="text-muted">Progression par niveaux – accès contrôlé</p>
                    <p><strong>Niveau actuel :</strong> <span class="badge bg-<?= $niveauColor ?>"><?= $niveauLabel ?></span></p>

                    <!-- Boutons -->
                    <a href="lessons.php" class="btn btn-primary mt-2 me-2">Commencer / Continuer</a>
                    
                </div>

                <div class="col-md-4 text-center">
                    <div class="progress-circle mx-auto">
                        <span><?= $progress ?>%</span>
                    </div>
                    <p class="mt-3"><?= $answeredQuestions ?> / <?= $totalQuestions ?> questions complétées</p>
                </div>

            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h6>Questions Totales</h6>
                    <h3><?= $totalQuestions ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h6>Réponses envoyées</h6>
                    <h3><?= $answeredQuestions ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h6>Progression</h6>
                    <h3><?= $progress ?>%</h3>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Animation cercle progression -->
<script>
const progress = <?= $progressCSS ?>;
const progressCircle = document.querySelector('.progress-circle');

let current = 0;
const interval = setInterval(() => {
    if(current >= progress){
        clearInterval(interval);
    } else {
        current++;
        progressCircle.style.background = `conic-gradient(#0d6efd ${current}%, #e9ecef 0%)`;
        progressCircle.querySelector('span').textContent = current + '%';
    }
}, 10);
</script>

</body>
</html>
