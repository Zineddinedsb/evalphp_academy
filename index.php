<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EvalPHP Academy - Développement Web PHP & MySQL</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fb;
        }
        .hero {
            background-color: #0d6efd;
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        .hero h1 {
            font-size: 3rem;
            margin-bottom: 20px;
        }
        .hero p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }
        .card:hover {
            transform: translateY(-5px);
            transition: 0.3s;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <a class="navbar-brand" href="#">🎓 EvalPHP Academy</a>
    <div class="ms-auto">
        <a href="auth/login.php" class="btn btn-outline-light me-2">Se connecter</a>
        <a href="auth/register.php" class="btn btn-primary">S’inscrire</a>
    </div>
</nav>

<!-- Hero section -->
<section class="hero">
    <div class="container">
        <h1>Apprenez PHP & MySQL de manière interactive</h1>
        <p>Évaluez vos compétences, suivez votre progression et obtenez des badges de réussite.</p>
        <a href="auth/register.php" class="btn btn-light btn-lg">Commencer maintenant</a>
    </div>
</section>

<!-- Cards de présentation -->
<section class="container my-5">
    <div class="row text-center">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100 p-3">
                <h5>📘 Cours complet</h5>
                <p>Apprenez les bases de PHP, PDO, MySQL et la sécurité Web de façon pratique.</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100 p-3">
                <h5>📝 Évaluations</h5>
                <p>Répondez à des questions techniques et suivez votre progression en temps réel.</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100 p-3">
                <h5>🏆 Badges & progrès</h5>
                <p>Gagnez des badges de réussite et visualisez votre progression grâce à des graphiques animés.</p>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="assets/css/style.css">

</section>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-4">
    &copy; <?= date("Y") ?> EvalPHP Academy. Tous droits réservés.
</footer>

</body>
</html>
