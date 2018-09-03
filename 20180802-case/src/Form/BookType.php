<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Genre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
//            customize date widget https://symfony.com/doc/current/reference/forms/types/date.html#widget
            ->add('release_date', DateType::class, array(
                'widget' => 'text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
//                placeholder for choice widget. not accepted
                'placeholder' => array(
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                ),
                'help' => 'Enter release date, for example 1778-08-25',
            ))
//            catalog date filled on SQL side by default value
//            ->add('catalog_date')
            ->add('rate')
//            render associated entity choice
            ->add('genre', EntityType::class, array(
                // looks for choices from this entity
                'class' => Genre::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'name',

                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ))
            ->add('author', EntityType::class, array(
                // looks for choices from this entity
                'class' => Author::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'name',
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
