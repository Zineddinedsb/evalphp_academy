<?php
require_once "../config/database.php";
session_start();
$error = '';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'] ?? 'student';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
    $stmt->execute([$email]);
    if($stmt->rowCount() > 0){
        $error = "Email déjà utilisé.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users(name,email,password,role) VALUES(?,?,?,?)");
        $stmt->execute([$name,$email,$password,$role]);
        $_SESSION['user'] = [
            'id' => $pdo->lastInsertId(),
            'name' => $name,
            'email' => $email,
            'role' => $role
        ];
        header("Location: ../student/dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Inscription | EvalPHP Academy</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
<div class="card p-4 shadow-sm" style="width: 350px;">
    <h3 class="text-center mb-3">Inscription</h3>
    <?php if($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="name" class="form-control mb-2" placeholder="Nom complet" required>
        <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
        <input type="password" name="password" class="form-control mb-2" placeholder="Mot de passe" required>
        <input type="hidden" name="role" value="student">
        <button class="btn btn-primary w-100" type="submit">S'inscrire</button>
        <p class="mt-2 text-center">Déjà un compte ? <a href="login.php">Se connecter</a></p>
    </form>
</div>
</body>
</html>
