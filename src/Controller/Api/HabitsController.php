<?php

namespace App\Controller\Api;

use App\Repository\HabitRepository;
use Mns\Buggy\Core\AbstractController;

class HabitController extends AbstractController
{
    private HabitRepository $habitRepository;

    public function __construct()
    {
        $this->habitRepository = new HabitRepository();
    }


    public function index()
    {
        return $this->json([
            'tickets' => $this->habitRepository->findAll()
        ]);
    }

}