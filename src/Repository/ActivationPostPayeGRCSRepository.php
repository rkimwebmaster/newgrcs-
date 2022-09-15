<?php

namespace App\Repository;

use App\Entity\ActivationPostPayeGRCS;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ActivationPostPayeGRCS>
 *
 * @method ActivationPostPayeGRCS|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActivationPostPayeGRCS|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActivationPostPayeGRCS[]    findAll()
 * @method ActivationPostPayeGRCS[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivationPostPayeGRCSRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivationPostPayeGRCS::class);
    }

    public function add(ActivationPostPayeGRCS $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ActivationPostPayeGRCS $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ActivationPostPayeGRCS[] Returns an array of ActivationPostPayeGRCS objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ActivationPostPayeGRCS
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
