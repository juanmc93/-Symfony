<?php

namespace App\Repository;

use App\Entity\Configuracion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Configuracion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Configuracion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Configuracion[]    findAll()
 * @method Configuracion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConfiguracionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Configuracion::class);
    }

    public function obtenerConfig()
    {
        return $this->getEntityManager()
            ->createQuery('
            select c.establecimiento, c.punto_emision, (c.sec_factura + f.id)+1 as sec_factura 
            FROM App\Entity\Configuracion c,App\Entity\Factura f where c.id=(select MAX(conf.id) 
            FROM App\Entity\Configuracion conf) and f.id=(select MAX(fac.id) FROM App\Entity\Factura fac)
            ')
            ->getSingleResult();
            // select c.establecimiento, c.punto_emision, (c.sec_factura + f.id)+1 as sec_factura 
            // FROM App\Entity\Configuracion c,App\Entity\Factura f where c.id=(select MAX(conf.id) 
            // FROM App\Entity\Configuracion conf) and f.id=(select MAX(fac.id) FROM App\Entity\Factura fac)
    }

    // /**
    //  * @return Configuracion[] Returns an array of Configuracion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Configuracion
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
