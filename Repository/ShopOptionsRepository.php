<?php

namespace Akyos\ShopBundle\Repository;

use Akyos\ShopBundle\Entity\ShopOptions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShopOptions|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShopOptions|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShopOptions[]    findAll()
 * @method ShopOptions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShopOptionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShopOptions::class);
    }

    // /**
    //  * @return ShopOptions[] Returns an array of ShopOptions objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ShopOptions
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
