<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 04.01.19
 * Time: 18:19
 */
namespace App\Form;
use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File;


class EditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('body');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'attr'=>['novalidate'=>'novalidate']
        ]);

    }
}