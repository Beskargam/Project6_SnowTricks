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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom'
                ]])
            ->add('content', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Description'
                ]])
            ->add('Categories', EntityType::class, [
                'class' => GroupTrick::class,
                'choice_label' => 'name',
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}