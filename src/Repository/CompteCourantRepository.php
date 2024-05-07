<?php

namespace App\Repository;

use App\Entity\CompteCourant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CompteCourant>
 *
 * @method CompteCourant|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompteCourant|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompteCourant[]    findAll()
 * @method CompteCourant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompteCourantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompteCourant::class);
    }

    public function findBySearchAndSort($searchBy, $searchQuery, $sortBy, $sortOrder)
    {
        $qb = $this->createQueryBuilder('c');

        if ($searchQuery && $searchBy) {
            $qb->andWhere('c.'.$searchBy.' LIKE :searchQuery') 
            ->setParameter('searchQuery', '%'.$searchQuery.'%');
        }

        $qb->orderBy('c.'.$sortBy, $sortOrder);

        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return CompteCourant[] Returns an array of CompteCourant objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CompteCourant
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
