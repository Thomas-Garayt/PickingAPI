<?php

namespace AppBundle\Form\Type\Position;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PositionType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('name');
        $builder->add('lane');
        $builder->add('landmark');
        $builder->add('shelf');
        $builder->add('section');
        $builder->add('enable');
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => \AppBundle\Entity\Position\Position::class,
            'csrf_protection' => false
        ]);
    }
}
