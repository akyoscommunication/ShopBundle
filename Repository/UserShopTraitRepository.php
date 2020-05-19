<?php

namespace Akyos\ShopBundle\Repository;

use Akyos\ShopBundle\Entity\UserShopTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserShopTrait|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserShopTrait|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserShopTrait[]    findAll()
 * @method UserShopTrait[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserShopTraitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserShopTrait::class);
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
