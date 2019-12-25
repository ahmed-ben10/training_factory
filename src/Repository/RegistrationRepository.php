<?php

namespace App\Repository;

use App\Entity\Registration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Registration|null find($id, $lockMode = null, $lockVersion = null)
 * @method Registration|null findOneBy(array $criteria, array $orderBy = null)
 * @method Registration[]    findAll()
 * @method Registration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegistrationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Registration::class);
    }

    public function getMemberCount($val){
        $em = $this->getEntityManager();
        $query =$em->createQuery("SELECT registration FROM App:Registration registration WHERE registration.lesson = :id");
        $query->execute(['id'=>$val]);
        return $query->getResult();
    }

    public function checkRepeatedRegistration($mem,$les){
        $em = $this->getEntityManager();
        $query =$em->createQuery("SELECT registration FROM App:Registration registration WHERE registration.member = :memberId AND registration.lesson = :lessonId");
        $query->execute(['memberId'=>$mem,'lessonId'=>$les]);
        return $query->getResult();
    }

    // /**
    //  * @return Registration[] Returns an array of Registration objects
    //  */

//    public function findByExampleField($value)
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    /*
    public function findOneBySomeField($value): ?Registration
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
