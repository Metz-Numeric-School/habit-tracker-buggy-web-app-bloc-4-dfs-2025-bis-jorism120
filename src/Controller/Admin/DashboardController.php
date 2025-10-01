<?php
namespace App\Controller\Admin;

use App\Repository\UserRepository;
use App\Repository\HabitRepository;
use App\Repository\HabitLogRepository;
use Mns\Buggy\Core\AbstractController;

class DashboardController extends AbstractController
{
    private UserRepository $userRepository;
    private HabitRepository $habitRepository;
    private HabitLogRepository $habitLogRepository;

    public function __construct()
    {   
        $this->userRepository = new UserRepository();
        $this->habitRepository = new HabitRepository();
        $this->habitLogRepository = new HabitLogRepository();
    }

    public function index()
    {
        $users = $this->userRepository->findAll();
        $totalUsers = count($users);
        $habits = $this->habitRepository->findAll();
        $totalHabits = count($habits);
        // Nombre total d'habitudes complétées aujourd'hui (anonyme)
        $completedToday = $this->habitLogRepository->countAllCompletedToday();

        return $this->render('admin/dashboard/index.html.php', [
            'totalUsers' => $totalUsers,
            'totalHabits' => $totalHabits,
            'completedToday' => $completedToday,
        ]);
    }
}
