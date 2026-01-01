<?php
require_once "../config/database.php";
require_once "../includes/auth_check.php";
if($_SESSION['user']['role']!=='teacher'){
    header("Location: ../auth/login.php");
    exit;
}

/* Liste des élèves */
$stmt = $pdo->query("SELECT * FROM users WHERE role='student'");
$students = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Dashboard Enseignant | EvalPHP Academy</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-primary px-4">
    <span class="navbar-brand">🎓 Dashboard Enseignant</span>
    <a href="../auth/logout.php" class="btn btn-light btn-sm">Déconnexion</a>
</nav>

<div class="container mt-5">
    <h3>Liste des élèves</h3>
    <table class="table table-striped table-hover shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($students as $s): ?>
            <tr>
                <td><?= $s['id'] ?></td>
                <td><?= htmlspecialchars($s['name']) ?></td>
                <td><?= htmlspecialchars($s['email']) ?></td>
                <td>
                    <a href="review.php?student_id=<?= $s['id'] ?>" class="btn btn-sm btn-primary">Voir / Noter</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
