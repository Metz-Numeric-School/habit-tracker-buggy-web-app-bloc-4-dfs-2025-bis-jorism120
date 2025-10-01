<?php
namespace App\Entity;

use App\Entity\AbstractEntity;

/**
 * Représente un log d'habitude
 */
class HabitLog extends AbstractEntity
{
    private $habit_id;
    private $log_date;
    private $status;
    private $created_at;

    public function getHabitId()
    {
        return $this->habit_id;
    }

    public function setHabitId($habit_id): self
    {
        $this->habit_id = $habit_id;
        return $this;
    }

    public function getLogDate()
    {
        return $this->log_date;
    }

    public function setLogDate($log_date): self
    {
        $this->log_date = $log_date;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }
}
