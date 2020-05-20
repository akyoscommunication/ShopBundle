<?php

namespace Akyos\ShopBundle\Repository;

use Akyos\ShopBundle\Entity\BaseUserShop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BaseUserShop|null find($id, $lockMode = null, $lockVersion = null)
 * @method BaseUserShop|null findOneBy(array $criteria, array $orderBy = null)
 * @method BaseUserShop[]    findAll()
 * @method BaseUserShop[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaseUserShopRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaseUserShop::class);
    }

    // /**
    //  * @return UserShopTrait[] Returns an array of UserShopTrait objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserShopTrait
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
