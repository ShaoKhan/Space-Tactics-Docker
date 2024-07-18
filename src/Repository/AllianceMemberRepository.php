<?php

namespace App\Repository;

use App\Entity\AllianceMember;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AllianceMember>
 */
class AllianceMemberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AllianceMember::class);
    }

    public function save(AllianceMember $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
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

    public function deleteByUserSlug($user): void
    {
        $this->createQueryBuilder('a')
            ->delete()
            ->where('a.user_slug = :user')
            ->setParameter('user', $user->getUuid())
            ->getQuery()
            ->execute();
    }
}
