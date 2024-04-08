<?php

namespace App\Repository;

use App\Entity\Mailing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Mailing>
 *
 * @method Mailing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mailing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mailing[]    findAll()
 * @method Mailing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MailingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mailing::class);
    }

    public function save(Mailing $mailing): void
    {
        $this->getEntityManager()->persist($mailing);
        $this->getEntityManager()->flush();
    }
}
