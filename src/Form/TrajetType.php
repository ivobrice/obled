<?php

namespace App\Form;

use App\Entity\Trajet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\Image;
use Vich\UploaderBundle\Form\Type\VichImageType;

class TrajetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('villeDept', null, [
                'attr' => ['id' => 'itineraire_villeDept', 'placeholder' => 'Lyon, France'],
                'label' => 'Votre ville de départ',
                'required' => false
            ])
            ->add('villeArrv', null, [
                'attr' => ['id' => 'itineraire_villeArrv', 'placeholder' => 'Toulouse, France'],
                'label' => 'Votre ville d\'arrivée',
                'required' => false
            ])
            ->add('dateDept', TextType::class, [
                'attr' => ['type' => 'datetime'],
                'label' => 'Date de départ',
                'mapped' => false,
                'required' => false
            ])
            ->add('heureDept', null, [
                'label' => 'Heures',
                'required' => false
            ])
            ->add('minuteDept', null, [
                'label' => 'Minutes',
                'required' => false
            ])
            ->add('nbrDePlace', null, [
                'label' => 'Nombre de place disponble dans votre voiture',
                'required' => false
            ])
            ->add('prixPlace', null, [
                'attr' => ['placeholder' => '25000'],
                'label' => 'Prix d\'une place',
                'help' => '0 si les places sont gratuites pour les passagers!',
                'required' => false
            ])
            ->add('rendezVsDept', null, [
                'attr' => ['placeholder' => 'Carrefoure Bellecoure'],
                'label' => 'Lieu du rendez-vous avec avec les passagers au départ ',
                'required' => false
            ])
            ->add('rendezVsArrv', null, [
                'attr' => ['placeholder' => 'Métro Arènes (Facultatif)'],
                'label' => 'Lieu du dépôt des passagers à l\'arrivée',
                'required' => false
            ])
            ->add('description', null, [
                'label' => 'Description de votre déplacement',
                'data' => 'Je pars dans le sud, j\'ai peu de place dans le coffre...
                Chaque passager devra avoir un bagage moyen',
                'required' => false
            ])
            ->add('restrictions', null, [
                'label' => 'Condictions du Voyage',
                'data' => 'Un bagage moyen par passager, voyage non fumeur, pas d\'animaux, pas de produits illicites, pas d\'objet ne pouvant suffire dans la voiture.',
                'required' => false
            ])
            ->add('marqVoiture', null, [
                'attr' => ['placeholder' => 'Renault 300'],
                'label' => 'Marque de votre voiture',
                'required' => false
            ])
            ->add('email', null, [
                'label' => 'Email',
                'help' => 'Obligatoire pour recevoir les réservations des passagers!',
                'required' => false
            ])
            ->add('phone', null, [
                'attr' => ['placeholder' => '07532214 (Obligatoire)'],
                'label' => 'Téléphone',
                'help' => 'Obligatoire pour être contacté par les passagers!',
                'required' => false
            ])
            ->add('prenom', null, [
                'attr' => ['placeholder' => 'Kean (Facultatif)'],
                'label' => 'Prénom',
                'required' => false
            ])
            ->add('anneeNaiss', TextType::class, [
                'attr' => ['placeholder' => '1990 (Facultatif)'],
                'label' => 'Année de naissance',
                'mapped' => false,
                'required' => false
            ])
            ->add('codeUser', null, [
                'label' => 'code du trajet',
                'mapped' => false,
                'attr' => ['placeholder' => 'a4dd97eF'],
                'required' => false
            ])
            ->add('id', HiddenType::class, ['mapped' => false])
            ->add('hashedCode', HiddenType::class, ['mapped' => false])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Photo',
                'required' => false,
                'allow_delete' => false,
                // 'delete_label' => 'supprimer la photo',
                'download_uri' => false,
                'imagine_pattern' => 'my_thumb_medium',
                'constraints' => [
                    new Image(['maxSize' => '15M', 'maxSizeMessage' => 'Image volumineuse, maximum 15M']),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trajet::class,
        ]);
    }
}
