<?php
namespace App\Guard;

class AdminGuard 
{
    /**
     * Vérifie que l'utilisateur possède les droits d'accès.
     */
    public static function check()
    {
        if(empty($_SESSION['admin']))
        {
            http_response_code(401);
            header("Location: /login");
            exit;
        }
    }
}