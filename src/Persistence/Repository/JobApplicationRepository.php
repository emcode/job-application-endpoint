<?php

namespace App\Persistence\Repository;

use App\Persistence\Entity\JobApplication;
use App\Persistence\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JobApplication>
 *
 * @method JobApplication|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobApplication|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobApplication[]    findAll()
 * @method JobApplication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobApplicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobApplication::class);
    }

    /**
     * @return JobApplication[]
     */
    public function findNotDisplayedYet(): array
    {
        $qb = $this->createQueryBuilder('ja');
        $ex = $qb->expr();
        $qb->where($ex->isNull('ja.firstDisplayDateTime'));
        return $qb->getQuery()->getResult();
    }

    public function save(JobApplication $jobApplication, bool $flush = true): void
    {
        $em = $this->getEntityManager();
        $em->persist($jobApplication);
        if ($flush) {
            $em->flush();
        }
    }
}
