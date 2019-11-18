<?php

namespace App\Repository;

use App\Entity\HorsForfait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method HorsForfait|null find($id, $lockMode = null, $lockVersion = null)
 * @method HorsForfait|null findOneBy(array $criteria, array $orderBy = null)
 * @method HorsForfait[]    findAll()
 * @method HorsForfait[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HorsForfaitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HorsForfait::class);
    }

    // /**
    //  * @return HorsForfait[] Returns an array of HorsForfait objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HorsForfait
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
