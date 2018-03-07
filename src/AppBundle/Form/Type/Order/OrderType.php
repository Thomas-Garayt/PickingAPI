<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 07/03/18
 * Time: 10:45
 */

namespace AppBundle\Form\Type\Order;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('status');
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => \AppBundle\Entity\Order\Order::class,
            'csrf_protection' => false
        ]);
    }

}