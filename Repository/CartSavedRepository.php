<?php

namespace Akyos\ShopBundle\Repository;

use Akyos\ShopBundle\Entity\CartSaved;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CartSaved|null find($id, $lockMode = null, $lockVersion = null)
 * @method CartSaved|null findOneBy(array $criteria, array $orderBy = null)
 * @method CartSaved[]    findAll()
 * @method CartSaved[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartSavedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CartSaved::class);
    }

    // /**
    //  * @return CartSaved[] Returns an array of CartSaved objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CartSaved
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
