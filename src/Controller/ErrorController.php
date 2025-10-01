<?php
namespace App\Controller;

use Mns\Buggy\Core\AbstractController;

/**
 * Gère les pages d'erreur
 */
class ErrorController extends AbstractController
{

   /**
    * Redirige vers la page 404
    */
   public function notFound()
   {
        return $this->render('error/404.html.php');
   }
}