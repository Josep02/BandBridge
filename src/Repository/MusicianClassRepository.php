<?php

namespace App\Repository;

use App\Entity\MusicianClass;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MusicianClass>
 *
 * @method MusicianClass|null find($id, $lockMode = null, $lockVersion = null)
 * @method MusicianClass|null findOneBy(array $criteria, array $orderBy = null)
 * @method MusicianClass[]    findAll()
 * @method MusicianClass[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MusicianClassRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MusicianClass::class);
    }

    //    /**
    //     * @return MusicianClass[] Returns an array of MusicianClass objects
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

    //    public function findOneBySomeField($value): ?MusicianClass
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
