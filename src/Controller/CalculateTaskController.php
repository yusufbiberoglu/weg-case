<?php

namespace App\Controller;

use App\Service\WeeklyProgramService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CalculateTaskController extends AbstractController
{

    private WeeklyProgramService $weeklyProgramService;

    public function __construct(WeeklyProgramService $weeklyProgramService)
    {
        $this->weeklyProgramService = $weeklyProgramService;
    }

    #[Route('/result', name: 'result')]
    public function getResult(): JsonResponse
    {
        $week = $this->weeklyProgramService->getWeek();
        $tasks = $this->weeklyProgramService->getResult();

        return new JsonResponse([$week, $tasks]);
    }
}