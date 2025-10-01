<?php
/**
 * @author Théo Gamory <theo.gamory@metznumericschool.fr>
 * @license MIT
 * @version 1.0
 * Cette application est un exemple de micro-framework PHP basé sur le modèle MVC.
 * Celle-ci est conçue pour être simple et facile à comprendre et s'inspire de Symfony.
 * L'application comporte volontairement des erreurs et des failles de sécurité pour illustrer les bonnes pratiques.
 * Il est recommandé de ne pas utiliser ce code en production.
 * Pour plus d'informations, consultez le README.md
 */

require '../vendor/autoload.php';
require '../config/bootstrap.php';

$kernel = new Mns\Buggy\Core\Kernel(
    new Mns\Buggy\Core\Router($routes)
);

$kernel->run();
