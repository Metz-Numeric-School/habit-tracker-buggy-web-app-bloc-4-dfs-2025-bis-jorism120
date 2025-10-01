<?php

namespace App\Entity;

use App\Entity\AbstractEntity;
use App\Repository\HabitLogRepository;

/**
 * Représente une habitude
 */
class Habit extends AbstractEntity
{
    private $user_id;
    private $name;
    private $description;
    private $created_at;

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($user_id): self
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description): self
    {
        $this->description = $description;
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

    /**
     * Vérifie si l'habitude est complétée aujourd'hui
     */
    public function isCompletedToday(): bool
    {
        $habitLogRepository = new HabitLogRepository();
        return $habitLogRepository->isCompletedToday($this->getId());
    }


    /**
     * Calcule le pourcentage de complétion sur les 7 derniers jours
     */
    public function getProgress(int $days = 7): int
    {
        $habitLogRepository = new HabitLogRepository();
        $completedDays = $habitLogRepository->countCompletedLastDays($this->getId(), $days);

        return (int)round(($completedDays / $days) * 100);
    }
}
