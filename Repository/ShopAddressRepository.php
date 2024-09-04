<?php

namespace Akyos\ShopBundle\Repository;

use Akyos\ShopBundle\Entity\ShopAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShopAddress|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShopAddress|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShopAddress[]    findAll()
 * @method ShopAddress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShopAddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShopAddress::class);
    }

    // /**
    //  * @return ShopAddress[] Returns an array of ShopAddress objects
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
    public function findOneBySomeField($value): ?ShopAddress
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
