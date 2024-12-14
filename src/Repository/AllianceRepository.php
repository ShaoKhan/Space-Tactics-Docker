<?php

namespace App\Repository;

use App\Entity\Alliance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Alliance>
 */
class AllianceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Alliance::class);
    }

    //    /**
    //     * @return Alliance[] Returns an array of Alliance objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Alliance
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function save(Alliance $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function deleteByUserSlug($user): void
    {
        $this->createQueryBuilder('a')
             ->delete()
             ->where('a.user_slug = :user')
             ->setParameter('user', $user->getUuid())
             ->getQuery()
             ->execute();
    }

    public function deleteByAllianceSlug($alliance): void
    {
        $this->createQueryBuilder('a')
             ->delete()
             ->where('a.alliance_slug = :alliance')
             ->setParameter('alliance', $alliance->getAllianceSlug())
             ->getQuery()
             ->execute();
    }
}
