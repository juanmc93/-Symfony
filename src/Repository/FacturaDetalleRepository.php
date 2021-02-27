<?php

namespace App\Repository;

use App\Entity\FacturaDetalle;
use App\Entity\Factura;
use App\Entity\Productos;
use App\Entity\Clientes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method FacturaDetalle|null find($id, $lockMode = null, $lockVersion = null)
 * @method FacturaDetalle|null findOneBy(array $criteria, array $orderBy = null)
 * @method FacturaDetalle[]    findAll()
 * @method FacturaDetalle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FacturaDetalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FacturaDetalle::class);
    }

    public function guardarDetalle($productos)
    {
        return $this->getEntityManager()
            ->createQuery('
                update  App\Entity\FacturaDetalle d set d.productos=:productos where d.id = (select MAX(de.id) FROM App\Entity\FacturaDetalle de)')
            ->setParameter('productos', $productos)->getSingleResult();
    }

    public function listarTodos()
    {
        return $this->getEntityManager()
            ->createQuery('
                select fd.id,f.id as id_factura, f.fecha,f.establecimiento,f.punto_emision,f.sec_factura,c.razon_social as cliente,e.razon_social as empresa,fd.productos,
                fd.subtotal,fd.iva,fd.total from App\Entity\FacturaDetalle fd,App\Entity\Factura f,App\Entity\Empresa e, App\Entity\Clientes c 
                where fd.facturas=f.id and f.clientes=c.id and f.empresa=e.id'
                )
            ->getResult();
    }

    // /**
    //  * @return FacturaDetalle[] Returns an array of FacturaDetalle objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FacturaDetalle
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
