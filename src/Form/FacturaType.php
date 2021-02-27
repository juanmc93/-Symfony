<?php

namespace App\Form;

use App\Entity\Factura;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FacturaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('establecimiento') 
            ->add('punto_emision')
            ->add('sec_factura')
            ->add('fecha')
            ->add('impuestos')
            ->add('total')
            ->add('subtotal')
            ->add('empresa')
            ->add('clientes')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Factura::class,
        ]);
    }
}
