<?php

namespace App\Controller;

use App\Entity\Factura;
use App\Entity\FacturaDetalle;
use App\Repository\FacturaDetalleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/facturadetalle")
 */

class FacturaDetalleController extends AbstractController
{
    /**
     * @Route("/", name="factura_detalle_index", methods={"GET"})
     */
    public function index(FacturaDetalleRepository $facturaDetalleRepository): Response
    {
        return $this->render('factura_detalle/index.html.twig', [
            'factura_detalles' => $facturaDetalleRepository->listarTodos(),
        ]);
    }

    /**
     * @Route("/{id}", name="factura_detalle_show", methods={"GET"})
     */
    public function show(Factura $factura): Response
    {
        return $this->render('factura_detalle/show.html.twig', [
            'factura' => $factura,
        ]);
    }


    public function listando()
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository(FacturaDetalle::class)->listarTodos();
        var_dump($products);
        die();
    }

    public function guardarDetalle($productos)
    {
        $em = $this->getDoctrine()->getManager();
        $resultProducto = $em->getRepository(FacturaDetalle::class)->guardarDetalle($productos);
        echo var_dump($resultProducto);
        die();
    }
}
