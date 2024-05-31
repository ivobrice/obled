<?php

namespace App\Repository;

use App\Entity\Trajet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trajet>
 *
 * @method Trajet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trajet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trajet[]    findAll()
 * @method Trajet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrajetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trajet::class);
    }

    public function getTrajetWithUsers($villeDept, $villeArrv, $paysDept, $paysArrv, $dateDept)
    {
        $qb = $this->createQueryBuilder('t');
        $qb->leftJoin('t.user', 'u')->addSelect('u');
        if ($paysDept && $paysArrv) {
            if ($paysDept == $paysArrv) {
                $qb->Where('t.villeDept = :villeDept')
                    ->setParameter('villeDept', $villeDept)
                    ->andWhere('t.villeArrv = :villeArrv')
                    ->setParameter('villeArrv', $villeArrv);
            }
            $qb->andwhere('t.paysDept = :paysDept')
                ->setParameter('paysDept', $paysDept)
                ->andWhere('t.paysArrv = :paysArrv')
                ->setParameter('paysArrv', $paysArrv);
        } else {
            if ($paysDept) {
                $qb->where('t.paysDept = :paysDept')
                    ->setParameter('paysDept', $paysDept)
                    ->andWhere('t.villeArrv = :villeArrv')
                    ->setParameter('villeArrv', $villeArrv);
            } elseif ($paysArrv) {
                $qb->andWhere('t.paysArrv = :paysArrv')
                    ->setParameter('paysArrv', $paysArrv);
                if ($villeDept) {
                    $qb->andWhere('t.villeDept = :villeDept')
                        ->setParameter('villeDept', $villeDept);
                }
            } else {
                $qb->andWhere('t.villeArrv = :villeArrv')
                    ->setParameter('villeArrv', $villeArrv);
                if ($villeDept) {
                    $qb->andWhere('t.villeDept = :villeDept')
                        ->setParameter('villeDept', $villeDept);
                }
            }
        }
        $qb->andwhere('t.publish = :publish')
            ->setParameter('publish', true)
            ->andWhere('t.dateDept >= :dateDept')
            ->setParameter('dateDept', $dateDept)
            ->orderBy('t.dateDept');
        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Trajet[] Returns an array of Trajet objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Trajet
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
