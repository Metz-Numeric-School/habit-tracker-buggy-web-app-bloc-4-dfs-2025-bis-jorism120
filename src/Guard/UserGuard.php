<?php
namespace App\Guard;

class UserGuard 
{
    /**
     * Vérifie que l'utilisateur est de type user
     */
    public static function check()
    {
        if(empty($_SESSION['user']))
        {
            http_response_code(401);
            header("Location: /login");
            exit;
        }
    }
}