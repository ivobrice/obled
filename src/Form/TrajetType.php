<?php

namespace App\Form;

use App\Entity\Trajet;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrajetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('villeDept')
            ->add('villeArrv')
            ->add('paysDept')
            ->add('paysArrv')
            ->add('dateDept', null, [
                'widget' => 'single_text',
            ])
            ->add('nbrDePlace')
            ->add('prixPlace')
            ->add('rendezVsDept')
            ->add('rendezVsArrv')
            ->add('description')
            ->add('restrictions')
            ->add('marqVoiture')
            ->add('nbrePlaceArr')
            ->add('prenom')
            ->add('email')
            ->add('phone')
            ->add('anneeNaiss', null, [
                'widget' => 'single_text',
            ])
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('updatedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('publish')
            ->add('codeUser')
            ->add('hashedCode')
            ->add('hashedCode2')
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trajet::class,
        ]);
    }
}
