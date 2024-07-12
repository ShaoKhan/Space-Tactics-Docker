<?php

namespace App\Repository;

use App\Entity\BuildingDependency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BuildingDependency>
 *
 * @method BuildingDependency|null find($id, $lockMode = null, $lockVersion = null)
 * @method BuildingDependency|null findOneBy(array $criteria, array $orderBy = null)
 * @method BuildingDependency[]    findAll()
 * @method BuildingDependency[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BuildingDependencyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BuildingDependency::class);
    }

    public function save(BuildingDependency $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BuildingDependency $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
