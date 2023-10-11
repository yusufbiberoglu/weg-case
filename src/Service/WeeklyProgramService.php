<?php

namespace App\Service;

use App\Repository\DeveloperRepository;

class WeeklyProgramService
{
    private $developerRepository;

    public function __construct(DeveloperRepository $developerRepository)
    {
        $this->developerRepository = $developerRepository;
    }
    public function getResult()
    {
        return $this->developerRepository->matchDevelopersWithTasksByDifficulty();
    }

    public function getWeek()
    {
        return $this->developerRepository->calculateWeeksToComplete();
    }
}