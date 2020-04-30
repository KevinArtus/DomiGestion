<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('civilite', ChoiceType::class, [
                'choices' => [
                    Client::CIV_MONSIEUR => Client::CIV_MONSIEUR,
                    Client::CIV_MADAME => Client::CIV_MADAME,
                ],
                'expanded' => false,
                'multiple' => false,
                'required' => true,
            ])
            ->add('prenom', TextType::class, ['required' => true])
            ->add('nom', TextType::class, ['required' => true])
            ->add('adresse', TextType::class, ['required' => false])
            ->add('codePostale', TextType::class, ['required' => false])
            ->add('ville', TextType::class, ['required' => false])
            ->add('fixe', TextType::class, ['required' => false])
            ->add('portable', TextType::class, ['required' => false])
            ->add('email', EmailType::class, ['required' => false])
            ->add('anniversaire', TextType::class, ['required' => false])
            ->add('isHote')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
            'csrf_protection' => false,
        ]);
    }
}
