<?php

namespace App\Form;

use App\Entity\Trajet;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrajetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('villeDept', null, [
                'attr' => ['placeholder' => 'Ex: Lyon, France'],
                'label' => 'Votre ville de Départ',
                'required' => false])
            ->add('villeArrv', null, [
                'attr' => ['placeholder' => 'Ex: Toulouse, France'],
                'label' => 'Votre ville d\'arrivée',
                'required' => false])
            ->add('dateDept', TextType::class, [
                'attr' => ['placeholder' => 'Ex: 29/04/2017'],
                'label' => 'Votre date de Départ',
                'required' => false])
            ->add('heureDept', null,[
                'label' => 'Heure de Départ',
                'required' => false])
            ->add('minuteDept', null, [
                'label' => 'Minute',
                'required' => false])
            ->add('nbrDePlace', null, [
                'label' => 'Nombre de place disponble dans votre voiture',
                'required' => false])
            ->add('prixPlace', null, [
                'attr' => ['placeholder' => 'Ex: 25000'],
                'label' => 'Prix d\'une place',
                'required' => false])
            ->add('rendezVsDept', null, [
                'attr' => ['placeholder' => 'Indiquez le lieu de rendez-vous avec les passagers au départ
                Ex: Carrefoure Bellecoure'],
                'label' => 'Lieu de rendez-vous au départ avec les passagers',
                'required' => false])
            ->add('rendezVsArrv', null, [
                'attr' => ['placeholder' => 'Ex: Métro Arènes (Facultatif)'],
                'label' => 'Lieu de dépôt des passagers à l\'arrivée',
                'required' => false])
            ->add('description', null, [
                'attr' => ['placeholder' => 'Ex: Je pars dans le sud, j\'ai peu de place dans le coffre...
                Chaque passager devra avoir un bagage moyen'],
                'label' => 'Description de votre déplacement',
                'required' => false])
            ->add('restrictions', null, [
                'attr' => ['placeholder' => 'Pas d\'animaux, pas de produits illicites, ni ne pouvant rentrer dans la voiture.'],
                'label' => 'Restrictions du déplacement',
                'required' => false])
            ->add('marqVoiture', null, [
                'attr' => ['placeholder' => 'Ex: Renault 3000 (Facultatif)'],
                'label' => 'Marque de votre voiture',
                'required' => false])
            // ->add('nbrePlaceArr', null, [
            //     'label' => 'Nombre de place à l\'arrière de votre voiture',
            //     'required' => false])
            ->add('prenom', null, [
                'attr' => ['placeholder' => 'Ex: Kean (Facultatif)'],
                'label' => 'Prénom',
                'required' => false])
            ->add('email', null, [
                'attr' => ['placeholder' => 'Ex: kean@mail.com (Obligatoire)'],
                'label' => 'Email',
                'required' => false])
            ->add('phone', null, [
                'attr' => ['placeholder' => 'Ex: 07532214 (Obligatoire)'],
                'label' => 'Numéro de téléphone',
                'required' => false])
            ->add('anneeNaiss', TextType::class, [
                'attr' => ['placeholder' => 'Ex: 1990 (Facultatif)'],
                'label' => 'Année de naissance',
                'required' => false])
            // ->add('user', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trajet::class,
        ]);
    }
}
