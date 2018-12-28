<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AddVideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('title')
            ->remove('content')
            ->remove('groupTrick')
            ->remove('images');
    }

    public function getParent()
    {
        return TrickType::class;
    }
}