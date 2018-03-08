<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 08/03/18
 * Time: 10:12
 */

namespace AppBundle\Form\Type\Course;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('stepValidated');
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => \AppBundle\Entity\Course\Course::class,
            'csrf_protection' => false
        ]);
    }

}