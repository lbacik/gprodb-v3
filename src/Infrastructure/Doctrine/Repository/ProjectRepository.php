<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 *
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    /**
     * @return Project[] Returns an array of Project objects
     */
    public function findBySearchString(string $searchString, int $offset, int $limit): array
    {
        return $this->createQueryBuilder('p')
            ->Where('p.name LIKE :searchString')
            ->orWhere('p.description LIKE :searchString')
            ->setParameter('searchString', '%' . $searchString . '%')
            ->orderBy('p.name', 'ASC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    public function countBySearchString(string $searchString): int
    {
        return $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->Where('p.name LIKE :searchString')
            ->orWhere('p.description LIKE :searchString')
            ->setParameter('searchString', '%' . $searchString . '%')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function save(Project $project): void
    {
        $this->getEntityManager()->persist($project);
        $this->getEntityManager()->flush();
    }
}
