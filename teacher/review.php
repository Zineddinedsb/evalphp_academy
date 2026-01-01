<?php
require_once "../config/database.php";
require_once "../includes/auth_check.php";

if ($_SESSION['user']['role'] !== 'teacher') {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_GET['student_id'])) {
    header("Location: dashboard.php");
    exit;
}

$student_id = (int) $_GET['student_id'];

/* Récupération de l’élève */
$stmt = $pdo->prepare("SELECT * FROM users WHERE id=? AND role='student'");
$stmt->execute([$student_id]);
$student = $stmt->fetch();
if (!$student) die("Élève introuvable !");

/* Récupération des réponses de l’élève avec info QCM */
$stmt = $pdo->prepare("
    SELECT a.*, q.title, q.category, q.type, q.correct_answer
    FROM answers a
    JOIN questions q ON a.question_id = q.id
    WHERE a.user_id=?
");
$stmt->execute([$student_id]);
$responses = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Notation automatique pour les QCM non notés */
foreach ($responses as $r) {
    if ($r['type'] === 'qcm' && $r['score'] === null) {
        $autoScore = ($r['answer_text'] === $r['correct_answer']) ? 100 : 0;
        $stmtAuto = $pdo->prepare("UPDATE answers SET score=? WHERE id=?");
        $stmtAuto->execute([$autoScore, $r['id']]);
        $r['score'] = $autoScore; // mise à jour pour affichage
    }
}

/* Soumission manuelle des notes pour questions ouvertes */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['scores'])) {
    foreach ($_POST['scores'] as $question_id => $score) {
        $score = (int)$score;
        if($score < 0) $score = 0;
        if($score > 100) $score = 100;
        $stmtUpdate = $pdo->prepare("UPDATE answers SET score=? WHERE user_id=? AND question_id=?");
        $stmtUpdate->execute([$score, $student_id, $question_id]);
    }
    $message = "✅ Notes enregistrées !";
}

/* Recharger les réponses après modification */
$stmt->execute([$student_id]);
$responses = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Correction | <?= htmlspecialchars($student['name']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-primary px-4">
    <span class="navbar-brand">🎓 Dashboard Enseignant</span>
    <a href="dashboard.php" class="btn btn-light btn-sm">Retour</a>
    <a href="../auth/logout.php" class="btn btn-light btn-sm">Déconnexion</a>
</nav>

<div class="container mt-5">
    <h3>Correction des réponses de : <?= htmlspecialchars($student['name']) ?></h3>
    <?php if(isset($message)): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>

    <?php if(empty($responses)): ?>
        <div class="alert alert-info">Cet élève n’a encore répondu à aucune question.</div>
    <?php else: ?>
        <form method="POST">
            <table class="table table-striped table-hover shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Question</th>
                        <th>Catégorie</th>
                        <th>Réponse de l'élève</th>
                        <th>Note (0-100)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($responses as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['title']) ?></td>
                        <td><?= htmlspecialchars($r['category']) ?></td>
                        <td><?= nl2br(htmlspecialchars($r['answer_text'])) ?></td>
                        <td>
                            <?php if($r['type'] === 'qcm'): ?>
                                <?= $r['score'] ?> (auto)
                            <?php else: ?>
                                <input type="number" name="scores[<?= $r['question_id'] ?>]" 
                                       value="<?= $r['score'] ?? 0 ?>" min="0" max="100" class="form-control" required>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-success">Enregistrer les notes</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
