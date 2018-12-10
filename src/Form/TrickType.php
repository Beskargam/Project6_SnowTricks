<?php
/**
 * Created by PhpStorm.
 * User: Aure
 * Date: 26.11.2018
 * Time: 16:18
 */

namespace App\Form;


use App\Entity\GroupTrick;
use App\Entity\Trick;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Nom du Trick :',
                'attr' => [
                    'placeholder' => false,
                ]])
            ->add('content', TextareaType::class, [
                'label' => 'Description du Trick :',
                'attr' => [
                    'placeholder' => false,
                ]])
            ->add('groupTrick', EntityType::class, [
                'label' => 'Catégorie du Trick :',
                'class' => GroupTrick::class,
                'choice_label' => 'name',
            ])
            ->add('images', CollectionType::class, [
                'entry_type' => ImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false,
                'by_reference' => false,
                'label' => 'Ajout d\'images :',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrement',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}