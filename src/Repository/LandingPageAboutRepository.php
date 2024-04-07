<?php

namespace App\Repository;

use App\Entity\LandingPageAbout;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LandingPageAbout>
 *
 * @method LandingPageAbout|null find($id, $lockMode = null, $lockVersion = null)
 * @method LandingPageAbout|null findOneBy(array $criteria, array $orderBy = null)
 * @method LandingPageAbout[]    findAll()
 * @method LandingPageAbout[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LandingPageAboutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LandingPageAbout::class);
    }

    //    /**
    //     * @return LandingPageAbout[] Returns an array of LandingPageAbout objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?LandingPageAbout
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
