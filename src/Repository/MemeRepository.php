<?php

namespace App\Repository;

use App\Entity\Meme;
use App\Entity\MemeUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Meme|null find($id, $lockMode = null, $lockVersion = null)
 * @method Meme|null findOneBy(array $criteria, array $orderBy = null)
 * @method Meme[]    findAll()
 * @method Meme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MemeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Meme::class);
    }


    /*
    public function findOneBySomeField($value): ?Meme
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
