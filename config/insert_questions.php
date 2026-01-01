<?php
require_once "database.php";

$questions = [
    [
        'title' => 'Base PHP 1',
        'question_text' => 'Explique la différence entre echo et print en PHP.',
        'category' => 'PHP'
    ],
    [
        'title' => 'Base PHP 2',
        'question_text' => 'Comment déclarer une variable en PHP ? Donne un exemple.',
        'category' => 'PHP'
    ],
    [
        'title' => 'PHP Orienté Objet',
        'question_text' => 'Qu’est-ce qu’une classe en PHP et comment l’instancier ?',
        'category' => 'PHP_OO'
    ],
    [
        'title' => 'PDO & MySQL 1',
        'question_text' => 'Montre comment se connecter à MySQL avec PDO.',
        'category' => 'MySQL'
    ],
    [
        'title' => 'PDO & MySQL 2',
        'question_text' => 'Comment exécuter une requête préparée en PDO ?',
        'category' => 'MySQL'
    ],
    [
        'title' => 'Sécurité 1',
        'question_text' => 'Qu’est-ce qu’une injection SQL et comment l’éviter ?',
        'category' => 'Sécurité'
    ],
    [
        'title' => 'Sécurité 2',
        'question_text' => 'Comment protéger un formulaire contre les attaques XSS ?',
        'category' => 'Sécurité'
    ],
    [
        'title' => 'MySQL 1',
        'question_text' => 'Quelle est la différence entre INNER JOIN et LEFT JOIN ?',
        'category' => 'MySQL'
    ],
    [
        'title' => 'PHP 1',
        'question_text' => 'Quelle est la différence entre == et === en PHP ?',
        'category' => 'PHP'
    ],
    [
        'title' => 'PDO 1',
        'question_text' => 'Explique l’intérêt des requêtes préparées avec PDO.',
        'category' => 'MySQL'
    ],
];

try {
    $stmt = $pdo->prepare("INSERT INTO questions(title, question_text, category) VALUES(?,?,?)");

    foreach($questions as $q){
        $stmt->execute([$q['title'],$q['question_text'],$q['category']]);
    }

    echo "✅ 10 questions insérées avec succès !";

} catch (PDOException $e){
    die("Erreur : ".$e->getMessage());
}
?>
