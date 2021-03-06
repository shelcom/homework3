<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 06.12.18
 * Time: 11:24
 */

namespace App\Form;
use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('body')
            ->add('image', FileType::class, array(
                'label' => 'Image',
                'data_class' => null,
                'required'    => false
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'attr'=>['novalidate'=>'novalidate']
        ]);

    }

}