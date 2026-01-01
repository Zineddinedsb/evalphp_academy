<?php
require_once "../config/database.php";
require_once "../includes/auth_check.php";

if ($_SESSION['user']['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit;
}

$userId = (int) $_SESSION['user']['id'];

/* Récupération des réponses + notes */
$stmt = $pdo->prepare("
    SELECT q.title, q.category, a.answer_text, a.score
    FROM answers a
    JOIN questions q ON a.question_id = q.id
    WHERE a.user_id = ?
");
$stmt->execute([$userId]);
$results = $stmt->fetchAll();

/* Calcul moyenne */
$totalScore = 0;
$count = 0;
foreach($results as $r){
    if($r['score'] !== null){
        $totalScore += $r['score'];
        $count++;
    }
}
$average = ($count > 0) ? round($totalScore / $count, 2) : 0;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Résultats | EvalPHP Academy</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-primary px-4">
    <span class="navbar-brand">🎓 EvalPHP Academy</span>
    <a href="dashboard.php" class="btn btn-light btn-sm">Dashboard</a>
    <a href="../auth/logout.php" class="btn btn-light btn-sm">Déconnexion</a>
</nav>

<div class="container mt-5">
    <h3 class="mb-4">Mes résultats</h3>

    <?php if(empty($results)): ?>
        <div class="alert alert-info">Vous n'avez encore répondu à aucune question.</div>
    <?php else: ?>
        <table class="table table-striped table-hover shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>Question</th>
                    <th>Catégorie</th>
                    <th>Ma réponse</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($results as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['title']) ?></td>
                        <td><?= htmlspecialchars($r['category']) ?></td>
                        <td><?= nl2br(htmlspecialchars($r['answer_text'])) ?></td>
                        <td>
                            <?php if($r['score'] !== null): ?>
                                <?= $r['score'] ?> / 100
                            <?php else: ?>
                                En attente
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="alert alert-success">
            <strong>Moyenne générale :</strong> <?= $average ?> / 100
        </div>
    <?php endif; ?>
</div>

</body>
</html>
