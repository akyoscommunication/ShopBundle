<?php

namespace Akyos\ShopBundle\Repository;

use Akyos\ShopBundle\Entity\OrderMail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderMail|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderMail|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderMail[]    findAll()
 * @method OrderMail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderMailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderMail::class);
    }

    // /**
    //  * @return OrderMail[] Returns an array of OrderMail objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OrderMail
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
