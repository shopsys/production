<?php

namespace Shopsys\ShopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class YesNoType extends AbstractType
{
    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => [
                t('Yes') => true,
                t('No') => false,
            ],
            'choice_name' => function ($choice) {
                return $choice ? 'yes' : 'no';
            },
            'choices_as_values' => true, // Switches to Symfony 3 choice mode, remove after upgrade from 2.8
            'multiple' => false,
            'expanded' => true,
            'placeholder' => false,
        ]);
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return ChoiceType::class;
    }
}
