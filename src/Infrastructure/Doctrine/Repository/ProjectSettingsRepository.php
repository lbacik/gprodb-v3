<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Type\ProjectSettings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProjectSettings>
 *
 * @method ProjectSettings|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectSettings|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectSettings[]    findAll()
 * @method ProjectSettings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectSettingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectSettings::class);
    }

    public function save(ProjectSettings $settings): void
    {
        $this->getEntityManager()->persist($settings);
        $this->getEntityManager()->flush();
    }

    //    /**
    //     * @return ProjectSettings[] Returns an array of ProjectSettings objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ProjectSettings
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
