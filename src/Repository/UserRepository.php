<?php
namespace App\Repository;

use App\Entity\User;
use App\Utils\EntityMapper;
use Mns\Buggy\Core\AbstractRepository;

/**
 * Gère la persistance des utilisateurs.
 */
class UserRepository extends AbstractRepository
{
    /**
     * Retourne l'ensemble des utilisateurs
     */
    public function findAll()
    {
        $users = $this->getConnection()->query("SELECT * FROM mns_user");
        return EntityMapper::mapCollection(User::class, $users->fetchAll());
    }

    /**
     * Récupère un utilisateur spécifique
     * @param int $id ID de l'utilisateur
     */
    public function find(int $id)
    {
        $user = $this->getConnection()->query("SELECT * FROM mns_user WHERE id = $id");
        return EntityMapper::map(User::class, $user);
    }

    /**
     * Récupère un utilisateur spécifique via son mail
     * @param string $mail Email de l'utilisateur
     */
    public function findByEmail(string $email)
    {
        $sql = "SELECT * FROM mns_user WHERE email = '$email'";
        $query = $this->getConnection()->query($sql);
        return EntityMapper::map(User::class, $query->fetch());
    }

    /**
     * Créer un utilisateur 
     * @param array $data Données constuant le nouvel utilisateur a créer.
     */
    public function insert(array $data = array())
    {
        $isAdmin = $data['isadmin'];

        $lastname  = $data['lastname'];

        $firstname = $data['firstname'];

        $email = $data['email'];

        $password =  $data['password'];

        $sql = "INSERT INTO mns_user (lastname, firstname, email, password, isadmin) VALUES (?, ?, ?, ?, ?)";
        $query = $this->getConnection()->prepare($sql);
        //$query->execute(['lastname' => $lastname], ['firstname' => $firstname],['email' => $email],['password' =>$password],['isadmin' => $isAdmin]);
        $query->execute([$lastname, $firstname,$email,$password,$isAdmin]);
        return $this->getConnection()->lastInsertId();
    }
}