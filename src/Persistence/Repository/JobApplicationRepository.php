<?php

namespace App\Persistence\Repository;

use App\Persistence\Entity\JobApplication;
use App\Persistence\Entity\User;
use DateTimeImmutable;
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
     * @return int[]
     */
    public function findIdsNotDisplayedYet(): array
    {
        $qb = $this->createQueryBuilder('ja');
        $qb->select('ja.id');
        $ex = $qb->expr();
        $qb->where($ex->isNull('ja.firstDisplayDateTime'));
        return $qb->getQuery()->getSingleColumnResult();
    }

    public function markIdsAsAlreadyDisplayed(array $idsToMark): int
    {
        if (empty($idsToMark)) {
            return 0;
        }

        $qb = $this->createQueryBuilder('ja');
        $qb->update();
        $qb->set('ja.firstDisplayDateTime', ':displayDateTime');
        $qb->setParameter(':displayDateTime', (new DateTimeImmutable())->format('Y-m-d H:i:s'));
        $ex = $qb->expr();
        $qb->where($ex->in('ja.id', ':idsToMark'));
        $qb->setParameter(':idsToMark', $idsToMark);
        return $qb->getQuery()->execute();
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
