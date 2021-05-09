<?php

namespace App\Repository;

use App\Entity\UserCurrency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserCurrency|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserCurrency|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserCurrency[]    findAll()
 * @method UserCurrency[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserCurrencyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserCurrency::class);
    }

    // /**
    //  * @return UserCurrency[] Returns an array of UserCurrency objects
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
    public function findOneBySomeField($value): ?UserCurrency
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
