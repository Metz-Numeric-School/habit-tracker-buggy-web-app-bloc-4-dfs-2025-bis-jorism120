<?php
namespace App\Controller;

use App\Guard\AdminGuard;
use App\Repository\UserRepository;
use Mns\Buggy\Core\AbstractController;

/**
 * Gère les inscriptions
 */
class RegisterController extends AbstractController
{

    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    /**
     * Vérifie et transmet les données saisies dans le formulaire d'inscription.
     */
    public function index()
    {

        $errors = [];
        $newUser = [];
        $passwordTmp = "";
        if(!empty($_POST['user'])) {

            $user = $_POST['user'];

            
            if(empty($user['lastname']))
                $errors['lastname'] = 'Le Nom est obligatoire';

            if(empty($user['firstname']))
                $errors['firstname'] = 'Le Prénom est obligatoire';

            if(empty($user['email']))
                $errors['email'] = 'L\'email est obligatoire';

            if(empty($user['password']))
                $errors['password'] = 'Le mot de passe est obligatoire';


            if(count($errors) == 0) {
                // Par défaut l'utilisateur n'est pas admin
                $user['isadmin'] = 0;

                $newUser['lastname'] = htmlspecialchars($user['lastname']);
                $newUser['firstname'] = htmlspecialchars($user['lastname']);
                $newUser['email'] = htmlspecialchars($user['email']);
                $passwordTmp = htmlspecialchars($user['password']);
                $newUser['password'] = password_hash($passwordTmp, PASSWORD_DEFAULT);

                // On persite les informations en BDD
                $id = $this->userRepository->insert($newUser);

                // On authentifie l'utilsateur directement
                $_SESSION['user'] = [
                    'id' => $id,
                    'username' => $user['firstname']
                ];

                // On redirige vers son dashboard
                header("Location: /user/ticket");
                exit;
            }
        }

        return $this->render('register/index.html.php', [
            'title' => 'Inscription',
            'errors' => $errors
        ]);
    }


}