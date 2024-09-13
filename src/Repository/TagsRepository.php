<?php

namespace App\Repository;

use App\Entity\Tags;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @extends ServiceEntityRepository<Tags>
 */
class TagsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tags::class);
    }


    public function getAllGaleriesTags(array $arraySelectedTags)
    {
        //Ramène tous les tags existants pour le parent "Galeries"
        $listeTags = $this->createQueryBuilder('t');

        //Ramène que les tags n'ayant pas déjà été sélectionnés
        if (count($arraySelectedTags) > 0) {
            $listeTags->andWhere('t.id NOT IN(:arraySelectedTags)')
                ->setParameter('arraySelectedTags', $arraySelectedTags);
        }
        $listeTags->andWhere('t.parent=:parent')
            ->setParameter('parent', 'galeries')
            ->orderBy('t.titre', 'ASC');

        return $listeTags->getQuery()->getResult();
    }
    //    /**
    //     * @return Tags[] Returns an array of Tags objects
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

    //    public function findOneBySomeField($value): ?Tags
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
