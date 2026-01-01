<?php
require_once "../includes/auth_check.php";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Table des matières | EvalPHP Academy</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
body { font-family: 'Poppins', sans-serif; background-color: #f5f7fb; }
h2 { margin-top: 20px; }
.part { margin-bottom: 30px; }
.part h3 { color: #0d6efd; cursor: pointer; }
.lesson-content { display: none; background-color: #fff; padding: 15px; border-radius: 5px; margin-top: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);}
</style>
</head>
<body>
<div class="container mt-5">
    <h2>📚 Table des matières interactive</h2>

    <!-- Partie 1 -->
    <div class="part">
        <h3 class="toggle-lesson" data-target="lesson1">Découvrir la fonctionnalité de PHP</h3>
        <div id="lesson1" class="lesson-content">
            <p><strong>Faites la différence entre site statique et dynamique :</strong></p>
            <p>On considère qu'il existe deux types de sites web :</p>
            <ul>
                <li>Les sites statiques</li>
                <li>Les sites dynamiques</li>
            </ul>
            <p><strong>Découvrez le principe d'un site statique :</strong></p>
            <p>Un site statique est réalisé uniquement à l'aide des langages HTML et CSS. Il fonctionne très bien, mais son contenu ne peut pas être mis à jour automatiquement : il faut que le webmaster modifie le code source pour y ajouter des nouveautés.</p>
            <p>Ce n'est pas très pratique quand on doit mettre à jour son site plusieurs fois dans la même journée…</p>
            <p>Un site statique est adapté pour un site « vitrine » (pour présenter par exemple son entreprise), mais sans aller plus loin.</p>
            <p>Ce type de site se fait de plus en plus rare aujourd'hui, car dès que l'on rajoute un élément d'interaction (comme un formulaire de contact), on ne parle plus de site statique mais de site dynamique.</p>
            <p><strong>Découvrez le principe d'un site dynamique :</strong></p>
        </div>
    </div>

    <!-- Partie 2 -->
    <div class="part">
        <h3 class="toggle-lesson" data-target="lesson2">Réaliser un site web dynamique avec PHP</h3>
        <div id="lesson2" class="lesson-content">
            <ul>
                <li>Décrivez les éléments de votre projet à l'aide de variables</li>
                <li>Adaptez le comportement de votre application à l'aide des conditions</li>
                <li>Affichez une liste de recettes à l'aide des boucles</li>
                <li>Organisez vos données à l'aide des tableaux</li>
            </ul>
        </div>
    </div>

</div>

<script>
// Toggle les leçons quand on clique sur le titre
$(document).ready(function(){
    $(".toggle-lesson").click(function(){
        var target = $(this).data("target");
        $("#" + target).slideToggle();
    });
});
</script>

</body>
</html>
