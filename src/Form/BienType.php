<?php

namespace App\Form;

use App\Entity\Acheteur;
use App\Entity\Agent;
use App\Entity\Bien;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BienType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('avecJardin')
            ->add('urlImg')
            ->add('metresCarres')
            ->add('etat',ChoiceType::class,[
                'choices' => [
                    'Excellent' => 'excellent',
                    'Renovation' => 'renovation',
                    'Rafraichir' => 'rafraichir',
                ],
            ])
            ->add('agent', EntityType::class,[
                'class' => 'App\Entity\Agent',
                'choice_label' => function (Agent $agent) {
                    return $agent->getFullName();
                }
            ])
            ->add('acheteurs', EntityType::class,[
                'class' => 'App\Entity\Acheteur',
                'choice_label' => function (Acheteur $acheteur) {
                    return $acheteur->getFullName();
                },
                'multiple' => true,
                'expanded' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bien::class,
        ]);
    }
}
