<?php

namespace App\Repository;

use App\Entity\ShoppingCartDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShoppingCartDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShoppingCartDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShoppingCartDetails[]    findAll()
 * @method ShoppingCartDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShoppingCartDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShoppingCartDetails::class);
    }

    public function save($object): void
    {
        $this->_em->persist($object);
        $this->_em->flush();
    }

    // /**
    //  * @return ShoppingCartDetails[] Returns an array of ShoppingCartDetails objects
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
    public function findOneBySomeField($value): ?ShoppingCartDetails
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
