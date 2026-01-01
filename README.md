# evalphp_academy

**Plateforme éducative PHP/MySQL pour l'apprentissage et l'évaluation des étudiants via QCM.**

## Description

**evalphp_academy** est une plateforme en ligne qui permet aux étudiants de suivre des cours et de passer des examens sous forme de QCM, tandis que les enseignants peuvent créer des cours, ajouter des questions et suivre les résultats des étudiants.

## Fonctionnalités

- Gestion sécurisée des utilisateurs (student / teacher)
- Création et organisation des cours et leçons
- Ajout et gestion des questions QCM
- Calcul automatique des scores et suivi des résultats
- Backend structuré et sécurisé avec PDO et requêtes préparées

## Technologies utilisées

- PHP 8+
- MySQL
- HTML / CSS / JavaScript

## Installation

1. Cloner le dépôt:
```bash
git clone https://github.com/votreutilisateur/evalphp_academy.git
```
2. Configurer la base de données dans `config/database.php`
3. Importer le fichier SQL fourni pour créer les tables
4. Lancer le projet sur votre serveur local (XAMPP, WAMP, ou MAMP)

## Structure du projet

```
evalphp_academy/
│
├── assets/
│   └── uploads/
├── config/
│   ├── database.php
│   └── insert_question.php
├── includes/
│   ├── header.php
│   ├── footer.php
│   └── auth_check.php
├── auth/
│   ├── login.php
│   ├── register.php
│   └── logout.php
├── student/
│   ├── dashboard.php
│   ├── lesson.php
│   ├── lesson_view.php
│   ├── questions.php
│   └── result.php
├── teacher/
│   ├── dashboard.php
│   └── review.php
├── index.php
└── .htaccess
```

## Contribution

Les contributions sont les bienvenues. Merci de forker le projet et de soumettre vos pull requests.

## Licence

Projet développé à des fins pédagogiques.
