<?php

namespace App\Repository;

use App\Entity\Investment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Investment>
 *
 * @method Investment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Investment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Investment[]    findAll()
 * @method Investment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvestmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Investment::class);
    }

    public function add(Investment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Investment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Investissement total par crypto par date 
     * @return Investment[] Returns an array of Investment objects
     */
    public function findChartTotal(): array
    {
        return $this->createQueryBuilder('i')
        ->select('i.date, sum(i.total) as total')
            /* ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value) */
            ->groupBy('i.date')
            ->orderBy('i.date', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Investissement total par crypto par date 
     * @return Investment[] Returns an array of Investment objects
     */
    public function findGlobalTotalByDate($date): array
    {
        return $this->createQueryBuilder('i')
        ->select('sum(i.total) as total')
            ->andWhere('i.date = :date')
            ->setParameter('date', $date) 
            ->groupBy('i.date')
            //->setMaxResults(10)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

//    /**
//     * @return Investment[] Returns an array of Investment objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    public function findOneByDateAndCryptoID($value,$date): ?Investment
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.crypto = :crypto')
            ->andWhere('i.date = :date')
            ->setParameter('crypto', $value)
            ->setParameter('date', $date)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
