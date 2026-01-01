<?php
require_once "../config/database.php";
require_once "../includes/auth_check.php";

if ($_SESSION['user']['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit;
}

$userId = (int) $_SESSION['user']['id'];

/* ⏱️ Durée de l'examen (ex : 10 minutes) */
$examDuration = 10 * 60; // secondes

$score = 0;
$totalQcm = 0;

/* 📩 Soumission des réponses */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['answers'])) {

    $pdo->beginTransaction();

    try {
        foreach ($_POST['answers'] as $question_id => $answer_text) {

            $question_id = (int)$question_id;
            $answer_text = htmlspecialchars($answer_text, ENT_QUOTES, 'UTF-8');

            /* Récupérer question */
            $stmtQ = $pdo->prepare(
                "SELECT type, correct_answer FROM questions WHERE id=?"
            );
            $stmtQ->execute([$question_id]);
            $question = $stmtQ->fetch();

            /* ✅ Correction automatique QCM */
            if ($question && $question['type'] === 'qcm') {
                $totalQcm++;
                if ($answer_text === $question['correct_answer']) {
                    $score++;
                }
            }

            /* Vérifier si déjà répondu */
            $check = $pdo->prepare(
                "SELECT id FROM answers WHERE user_id=? AND question_id=?"
            );
            $check->execute([$userId, $question_id]);

            if ($check->rowCount() === 0) {
                $insert = $pdo->prepare(
                    "INSERT INTO answers (user_id, question_id, answer_text)
                     VALUES (?, ?, ?)"
                );
                $insert->execute([$userId, $question_id, $answer_text]);
            }
        }

        $pdo->commit();
        $message = "Examen terminé avec succès ✅";

    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Erreur lors de la soumission ❌";
    }
}

/* 📋 Questions */
$stmt = $pdo->query("SELECT * FROM questions");
$questions = $stmt->fetchAll();

/* 🧾 Réponses existantes */
$stmtAns = $pdo->prepare("SELECT * FROM answers WHERE user_id=?");
$stmtAns->execute([$userId]);
$answersRaw = $stmtAns->fetchAll();

$answeredQuestions = [];
foreach ($answersRaw as $a) {
    $answeredQuestions[$a['question_id']] = $a['answer_text'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Examen | EvalPHP Academy</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<nav class="navbar navbar-dark bg-primary px-4">
    <span class="navbar-brand">🎓 EvalPHP Academy</span>
    <a href="dashboard.php" class="btn btn-light btn-sm">Dashboard</a>
    <a href="../auth/logout.php" class="btn btn-light btn-sm">Déconnexion</a>
</nav>

<div class="container mt-4">

    <!-- ⏱️ Chronomètre -->
    <div id="timer" class="alert alert-warning text-center fw-bold"></div>

    <?php if(isset($message)): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>

    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <!-- 🎯 Résultat QCM -->
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <div class="alert alert-info text-center">
            🎯 <strong>Score QCM :</strong> <?= $score ?> / <?= $totalQcm ?>
        </div>
    <?php endif; ?>

    <h3 class="mb-4">Examen PHP & MySQL</h3>

    <form method="POST" id="examForm">

        <?php foreach ($questions as $q): ?>
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5><?= htmlspecialchars($q['title']) ?> (<?= htmlspecialchars($q['category']) ?>)</h5>
                    <p><?= nl2br(htmlspecialchars($q['question_text'])) ?></p>

                    <?php if (isset($answeredQuestions[$q['id']])): ?>
                        <div class="alert alert-secondary">
                            <strong>Votre réponse :</strong><br>
                            <?= nl2br($answeredQuestions[$q['id']]) ?>
                        </div>
                    <?php else: ?>

                        <?php if ($q['type'] === 'qcm'): 
                            $options = json_decode($q['options'], true); ?>
                            <?php foreach ($options as $opt): ?>
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="radio"
                                           name="answers[<?= $q['id'] ?>]"
                                           value="<?= htmlspecialchars($opt) ?>"
                                           required>
                                    <label class="form-check-label">
                                        <?= htmlspecialchars($opt) ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <textarea
                                name="answers[<?= $q['id'] ?>]"
                                class="form-control"
                                rows="3"
                                placeholder="Votre réponse..."
                                required></textarea>
                        <?php endif; ?>

                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-success btn-lg">
                ✅ Terminer l'examen
            </button>
        </div>
    </form>
</div>

<!-- ⏱️ SCRIPT CHRONOMÈTRE -->
<script>
let timeLeft = <?= $examDuration ?>;

const timer = document.getElementById("timer");

const interval = setInterval(() => {
    let minutes = Math.floor(timeLeft / 60);
    let seconds = timeLeft % 60;

    timer.innerHTML =
        "⏱️ Temps restant : " +
        String(minutes).padStart(2, '0') + ":" +
        String(seconds).padStart(2, '0');

    if (timeLeft <= 0) {
        clearInterval(interval);
        alert("⏰ Temps écoulé ! L'examen est envoyé.");
        document.getElementById("examForm").submit();
    }

    timeLeft--;
}, 1000);
</script>

</body>
</html>
