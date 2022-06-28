<?php

namespace App\Repository;

use App\Entity\Sondage;
use Doctrine\ORM\Query;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Sondage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sondage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sondage[]    findAll()
 * @method Sondage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SondageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sondage::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Sondage $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findWithPagination() :Query {
        return$this->createQueryBuilder('s')
        ->getQuery();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Sondage $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findBySondageduneCategorie(){
        $qb = $this->createQueryBuilder('s');


        $qb
            ->join('APP\Entity\Categories', 'c' ,'WITH','c = s.categorie')

            ->innerjoin('App\Entity\Interroger', 'i' ,'WITH','i = s.questions')
            ->where('i.intitule =:intitule')         

            ->innerjoin('App\Entity\Reponse', 'r' ,'WITH','r = s.reponses')
            ->where('r.titre =:titre')            ->where('c.titre =:titre ')
            ->setParameter('titre','culture et loisirs')
            ->groupBy("s.categorie")

            ->orderBy('s.id', 'ASC');

        return $qb->getQuery()->getResult();
    
    }

    // /**
    //  * @return Sondage[] Returns an array of Sondage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sondage
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
