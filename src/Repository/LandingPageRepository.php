<?php

namespace App\Repository;

use App\Entity\LandingPage;
use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LandingPage>
 *
 * @method LandingPage|null find($id, $lockMode = null, $lockVersion = null)
 * @method LandingPage|null findOneBy(array $criteria, array $orderBy = null)
 * @method LandingPage[]    findAll()
 * @method LandingPage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LandingPageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LandingPage::class);
    }

    public function save(LandingPage $landingPage): void
    {
        $this->getEntityManager()->persist($landingPage);
        $this->getEntityManager()->flush();
    }

    //    /**
    //     * @return LandingPage[] Returns an array of LandingPage objects
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

    //    public function findOneBySomeField($value): ?LandingPage
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
