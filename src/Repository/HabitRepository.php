<?php
namespace App\Repository;

use App\Entity\Habit;
use App\Utils\EntityMapper;
use Mns\Buggy\Core\AbstractRepository;

/**
 * Gère la persistance des données des habitudes
 */
class HabitRepository extends AbstractRepository
{
    /**
     * Récupère l'ensemble des habitudes.
     */
    public function findAll()
    {
        $habits = $this->getConnection()->query("SELECT * FROM habits");
        return EntityMapper::mapCollection(Habit::class, $habits->fetchAll());
    }

    /**
     * Récupère une habitude spécifique
     * @param int $id ID de l'habitude
     */
    public function find(int $id)
    {
        $habit = $this->getConnection()->query("SELECT * FROM habits WHERE id = $id");
        return EntityMapper::map(Habit::class, $habit->fetch());
    }

    /**
     * Récupère l'ensemble des habitudes d'un utilisateur
     * @param int $id ID de l'utilisateur
     */
    public function findByUser(int $userId)
    {
        $sql = "SELECT * FROM habits WHERE user_id = $userId";
        $query = $this->getConnection()->query($sql);
        return EntityMapper::mapCollection(Habit::class, $query->fetchAll());
    }

     /**
     * Compte le nombre d'habitudes actives pour un utilisateur
     * @param int $id ID de l'utilisateur
     * 
     */
    public function countByUser(int $userId): int
    {
        $stmt = $this->getConnection()->prepare("SELECT COUNT(*) as total FROM habits WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $row = $stmt->fetch();
        return (int)($row['total'] ?? 0);
    }

    /**
     * Créer une nouvelle habitude 
     * @param array $data Ensemble des données consituant l'habitude a créer.
     */
    public function insert(array $data = array())
    {
        $userId = htmlspecialchars($data['user_id']);
        $name = htmlspecialchars($data['name']);   
        $description = htmlspecialchars($data['description']);
        $pdo = $this->getConnection();
        // Requête construite par concaténation (vulnérable)
        $sql = "INSERT INTO habits (user_id, name, description) VALUES ('?','?','?');";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId, $name,$description]);
        return $this->getConnection()->lastInsertId();
    }


    /**
     * Calcule le nombre de jours consécutifs où l'utilisateur a complété au moins une habitude
     * @param int $userId ID de l'utilisateur.
     */
    public function getStreak(int $userId): int
    {
        $pdo = $this->getConnection();

        $sql = "
            SELECT DISTINCT log_date
            FROM habit_logs hl
            JOIN habits h ON hl.habit_id = h.id
            WHERE h.user_id = :user_id AND hl.status = 1
            ORDER BY log_date DESC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        $dates = $stmt->fetchAll(\PDO::FETCH_COLUMN);

        $streak = 0;
        $today = new \DateTime();
        foreach ($dates as $dateStr) {
            $date = new \DateTime($dateStr);
            if ($date->format('Y-m-d') === $today->format('Y-m-d')) {
                $streak++;
                $today->modify('-1 day');
            } elseif ($date->format('Y-m-d') === $today->format('Y-m-d')) {
                // continue streak
                $streak++;
                $today->modify('-1 day');
            } else {
                break;
            }
        }

        return $streak;
    }

}
