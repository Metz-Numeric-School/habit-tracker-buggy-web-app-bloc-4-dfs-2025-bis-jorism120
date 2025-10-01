<?php
namespace App\Controller\Admin;

use App\Guard\AdminGuard;
use App\Repository\UserRepository;
use Mns\Buggy\Core\AbstractController;

class UserController extends AbstractController
{

    private UserRepository $userRepository;

    public function __construct()
    {   
        $this->userRepository = new UserRepository();
    }


    public function index()
    {
        AdminGuard::check();
        $users = $this->userRepository->findAll();
        return $this->render('admin/user/index.html.php', [
            'users' => $users,
        ]);
    }

    public function new()
    {
        $errors = [];
        $newUser = [];
        $passwordTmp = "";

        if(!empty($_POST['user']))
        {
            $user = $_POST['user'];
            
            if(empty($user['lastname']))
                $errors['lastname'] = 'Le Nom est obligatoire';

            if(empty($user['firstname']))
                $errors['firstname'] = 'Le Prénom est obligatoire';

            if(empty($user['email']))
                $errors['email'] = 'L\'email est obligatoire';

            if(empty($user['password']))
                $errors['password'] = 'Le mot de passe est obligatoire';

            
            if(count($errors) == 0)
            {
                $newUser['isadmin'] = htmlspecialchars($user['isadmin']);
                $newUser['lastname'] = htmlspecialchars($user['lastname']);
                $newUser['firstname'] = htmlspecialchars($user['lastname']);
                $newUser['email'] = htmlspecialchars($user['email']);
                $passwordTmp = htmlspecialchars($user['password']);
                $newUser['password'] = password_hash($passwordTmp, PASSWORD_DEFAULT);

                $id = $this->userRepository->insert($newUser);
                header('Location: /admin/user');
                exit;
            }
        }

        return $this->render('admin/user/new.html.php', [
            'errors' => $errors,
        ]);
    }
}