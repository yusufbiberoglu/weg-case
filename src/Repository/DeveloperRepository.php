<?php

namespace App\Repository;

use App\Entity\Developer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Developer>
 *
 * @method Developer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Developer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Developer[]    findAll()
 * @method Developer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeveloperRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Developer::class);
    }

    public function matchDevelopersWithTasksByDifficulty()
    {
        $qb = $this->createQueryBuilder('d');
        $qb->select('d.name AS developer_name, t.name AS task_name, t.time AS task_time, t.difficulty AS task_difficulty');
        $qb->from('App\Entity\Task', 't');
        $qb->where('t.difficulty = d.difficulty');
        $qb->orderBy('t.difficulty', 'ASC');

        return $qb->getQuery()->getResult();
    }

    public function calculateWeeksToComplete()
    {
        $qb = $this->createQueryBuilder('d');
        $qb->select('d.name AS developer_name, SUM(t.time) AS total_time');
        $qb->from('App\Entity\Task', 't');
        $qb->where('t.difficulty <= d.difficulty');
        $qb->groupBy('d.name');

        $result = $qb->getQuery()->getResult();

        $weeklyWorkHours = 45;

        $estimatedCompletionTimes = [];
        foreach ($result as $row) {
            $estimatedCompletionTimes[$row['developer_name']] = ceil($row['total_time'] / $weeklyWorkHours);
        }

        $maxCompletionTime = max($estimatedCompletionTimes);

        return  ceil($maxCompletionTime / 5);
    }


}
