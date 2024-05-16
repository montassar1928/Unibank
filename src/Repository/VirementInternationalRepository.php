<?php

namespace App\Repository;

use App\Entity\VirementInternational;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VirementInternational>
 *
 * @method VirementInternational|null find($id, $lockMode = null, $lockVersion = null)
 * @method VirementInternational|null findOneBy(array $criteria, array $orderBy = null)
 * @method VirementInternational[]    findAll()
 * @method VirementInternational[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VirementInternationalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VirementInternational::class);
    }

//    /**
//     * @return VirementInternational[] Returns an array of VirementInternational objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?VirementInternational
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
