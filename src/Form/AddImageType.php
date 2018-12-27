<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AddImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('title')
            ->remove('content')
            ->remove('groupTrick')
            ->remove('videos');
    }

    public function getParent()
    {
        return TrickType::class;
    }
}