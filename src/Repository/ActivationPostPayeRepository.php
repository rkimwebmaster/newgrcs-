<?php

namespace App\Repository;

use App\Entity\ActivationPostPaye;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ActivationPostPaye>
 *
 * @method ActivationPostPaye|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActivationPostPaye|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActivationPostPaye[]    findAll()
 * @method ActivationPostPaye[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivationPostPayeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivationPostPaye::class);
    }

    public function add(ActivationPostPaye $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ActivationPostPaye $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ActivationPostPaye[] Returns an array of ActivationPostPaye objects
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

//    public function findOneBySomeField($value): ?ActivationPostPaye
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
