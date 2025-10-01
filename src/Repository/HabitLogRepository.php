<?php
namespace App\Repository;

use App\Entity\HabitLog;
use App\Utils\EntityMapper;
use Mns\Buggy\Core\AbstractRepository;


/**
 * Gère la persistance des données des logs
 */
class HabitLogRepository extends AbstractRepository
{
    public function find($id)
    {
        $sql = "SELECT * FROM habit_logs WHERE id = :id";
        $query = $this->getConnection()->prepare($sql);
        $query->execute(['id' => $id]);
        $result = $query->fetch();
        return $result ? EntityMapper::map(HabitLog::class, $result) : null;
    }

    public function findAll()
    {
        $logs = $this->getConnection()->query("SELECT * FROM habit_logs");
        return EntityMapper::mapCollection(HabitLog::class, $logs->fetchAll());
    }

    public function findByHabit(int $habitId)
    {
        $sql = "SELECT * FROM habit_logs WHERE habit_id = $habitId ORDER BY log_date DESC";
        $query = $this->getConnection()->query($sql);
        return EntityMapper::mapCollection(HabitLog::class, $query->fetchAll());
    }

    public function insert(array $data = array())
    {
        $sql = "INSERT INTO habit_logs (habit_id, log_date, status) VALUES (:habit_id, :log_date, :status)";
        $query = $this->getConnection()->prepare($sql);
        $query->execute($data);
        return $this->getConnection()->lastInsertId();
    }

    /**
     * Compte le nombre d'habitudes complétées aujourd'hui pour un utilisateur
     */
    public function countCompletedToday(int $userId): int
    {
        $sql = "
            SELECT COUNT(*) as total
            FROM habit_logs hl
            JOIN habits h ON hl.habit_id = h.id
            WHERE h.user_id = :user_id
              AND hl.log_date = CURDATE()
              AND hl.status = 1
        ";

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        $row = $stmt->fetch();

        return (int)($row['total'] ?? 0);
    }

    /**
     * Vérifie si une habitude spécifique est complétée aujourd'hui
     */
    public function isCompletedToday(int $habitId): bool
    {
        $sql = "
            SELECT status
            FROM habit_logs
            WHERE habit_id = :habit_id
              AND log_date = CURDATE()
            LIMIT 1
        ";

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute(['habit_id' => $habitId]);
        $row = $stmt->fetch();

        return !empty($row) && $row['status'] == 1;
    }

    /**
     * Marque ou décoche une habitude pour aujourd'hui
     */
    public function toggleToday(int $habitId): void
    {
        $pdo = $this->getConnection();

        // Vérifie si un log existe aujourd'hui
        $sqlCheck = "SELECT id, status FROM habit_logs WHERE habit_id = :habit_id AND log_date = CURDATE() LIMIT 1";
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->execute(['habit_id' => $habitId]);
        $row = $stmtCheck->fetch();

        if ($row) {
            // Inverse le statut
            $newStatus = $row['status'] == 1 ? 0 : 1;
            $sqlUpdate = "UPDATE habit_logs SET status = :status WHERE id = :id";
            $stmtUpdate = $pdo->prepare($sqlUpdate);
            $stmtUpdate->execute([
                'status' => $newStatus,
                'id' => $row['id']
            ]);
        } else {
            // Crée un log pour aujourd'hui
            $sqlInsert = "INSERT INTO habit_logs (habit_id, log_date, status) VALUES (:habit_id, CURDATE(), 1)";
            $stmtInsert = $pdo->prepare($sqlInsert);
            $stmtInsert->execute(['habit_id' => $habitId]);
        }
    }

    
    public function countCompletedLastDays(int $habitId, int $days = 7): int
    {
        $sql = "
            SELECT COUNT(DISTINCT log_date) as completed_days
            FROM habit_logs
            WHERE habit_id = :habit_id
              AND status = 1
              AND log_date >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
        ";

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bindValue('habit_id', $habitId, \PDO::PARAM_INT);
        $stmt->bindValue('days', $days, \PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch();
        return (int)($row['completed_days'] ?? 0);
    }

    public function countAllCompletedToday(): int
    {
        $sql = "SELECT COUNT(*) as total FROM habit_logs WHERE status = 1 AND log_date = CURDATE()";
        $stmt = $this->getConnection()->query($sql);
        $row = $stmt->fetch();
        return (int)($row['total'] ?? 0);
    }
}
