<?php

namespace App\Repository;

use App\Entity\MessageGrandFournisseur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MessageGrandFournisseur>
 *
 * @method MessageGrandFournisseur|null find($id, $lockMode = null, $lockVersion = null)
 * @method MessageGrandFournisseur|null findOneBy(array $criteria, array $orderBy = null)
 * @method MessageGrandFournisseur[]    findAll()
 * @method MessageGrandFournisseur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageGrandFournisseurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MessageGrandFournisseur::class);
    }

    public function add(MessageGrandFournisseur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MessageGrandFournisseur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return MessageGrandFournisseur[] Returns an array of MessageGrandFournisseur objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MessageGrandFournisseur
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
