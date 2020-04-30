<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Reunion;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReunionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var $user \App\Entity\User */
        $user = $builder->getData()->getUser();

        $builder
            ->add('date')
            ->add('montantHt')
            ->add('montantTTC')
            ->add('hote', EntityType::class, [
                'class' => Client::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.isHote = 1')
                        ->orderBy('c.nom', 'ASC');
                },
                'required' => true,
                'multiple' => false
            ])
            ->add('participants', EntityType::class, [
                'class' => Client::class,
                'choices' =>$user->getClients(),
                'required' => false,
                'multiple' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reunion::class,
        ]);
    }
}
