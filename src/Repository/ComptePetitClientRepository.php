<?php

namespace App\Repository;

use App\Entity\ComptePetitClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ComptePetitClient>
 *
 * @method ComptePetitClient|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComptePetitClient|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComptePetitClient[]    findAll()
 * @method ComptePetitClient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComptePetitClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ComptePetitClient::class);
    }

    public function add(ComptePetitClient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ComptePetitClient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ComptePetitClient[] Returns an array of ComptePetitClient objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ComptePetitClient
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
