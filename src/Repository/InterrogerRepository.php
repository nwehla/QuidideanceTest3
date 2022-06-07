<?php

namespace App\Repository;

use Doctrine\ORM\Query;
use App\Entity\Interroger;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Interroger|null find($id, $lockMode = null, $lockVersion = null)
 * @method Interroger|null findOneBy(array $criteria, array $orderBy = null)
 * @method Interroger[]    findAll()
 * @method Interroger[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InterrogerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Interroger::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Interroger $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Interroger $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findWithPagination() :Query {
        return$this->createQueryBuilder('i')
        ->getQuery();
    }

    /**
     * Recherche les questions en fonction du formulaire
     * @return void 
     */
    public function search($mots = null, $categorie = null){
        $query = $this->createQueryBuilder('i');
        $query->where('i.active = 1');
        if($mots != null){
            $query->andWhere('MATCH_AGAINST(i.intitule) AGAINST (:mots boolean)>0')
                ->setParameter('mots', $mots);
        }
        if($categorie != null){
            $query->leftJoin('i.categorie', 'c');
            $query->andWhere('c.id = :id')
                ->setParameter('id', $categorie);
        }
        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return Interroger[] Returns an array of Interroger objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Interroger
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}




    // /**
    //  * Recherche les annonces en fonction du formulaire
    //  * @return void 
    //  */
    // public function search($mots = null, $categorie = null){
    //     $query = $this->createQueryBuilder('a');
    //     $query->where('a.active = 1');
    //     if($mots != null){
    //         $query->andWhere('MATCH_AGAINST(a.title, a.content) AGAINST (:mots boolean)>0')
    //             ->setParameter('mots', $mots);
    //     }
    //     if($categorie != null){
    //         $query->leftJoin('a.categories', 'c');
    //         $query->andWhere('c.id = :id')
    //             ->setParameter('id', $categorie);
    //     }
    //     return $query->getQuery()->getResult();
    // }
