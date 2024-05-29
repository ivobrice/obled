<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Entity\Trajet;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('phonePassager')
            ->add('nbrDePlaceRsrv')
            ->add('prixPlaceRsrv')
            ->add('villeDept')
            ->add('villeArrv')
            ->add('dateDept', null, [
                'widget' => 'single_text',
            ])
            ->add('mailPassager')
            ->add('mailChauf')
            ->add('phoneChauf')
            ->add('dateValidationClient', null, [
                'widget' => 'single_text',
            ])
            ->add('paiement')
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
            ->add('trajet', EntityType::class, [
                'class' => Trajet::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
