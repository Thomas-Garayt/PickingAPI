<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class UserType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('firstname');
        $builder->add('lastname');
        $builder->add('email', EmailType::class);
        $builder->add('plainPassword', TextType::class);
        $builder->add('username');
        $builder->add('identifier');
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => \AppBundle\Entity\User::class,
            'csrf_protection' => false
        ]);
    }
}
