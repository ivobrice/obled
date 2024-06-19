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
                'attr' => ['placeholder' => 'Ex: Lyon, France'],
                'label' => 'Votre ville de Départ',
                'required' => false
            ])
            ->add('villeArrv', null, [
                'attr' => ['placeholder' => 'Ex: Toulouse, France'],
                'label' => 'Votre ville d\'arrivée',
                'required' => false
            ])
            ->add('dateDept', TextType::class, [
                'attr' => ['placeholder' => 'Ex: 29/04/2017'],
                'label' => 'Votre date de Départ',
                'mapped' => false,
                'required' => false
            ])
            ->add('heureDept', null, [
                'label' => 'Heure de Départ',
                'required' => false
            ])
            ->add('minuteDept', null, [
                'label' => 'Minute',
                'required' => false
            ])
            ->add('nbrDePlace', null, [
                'label' => 'Nombre de place disponble dans votre voiture',
                'required' => false
            ])
            ->add('prixPlace', null, [
                'attr' => ['placeholder' => 'Ex: 25000'],
                'label' => 'Prix d\'une place',
                'help' => '0 si les places sont gratuites pour les passagers!',
                'required' => false
            ])
            ->add('rendezVsDept', null, [
                'attr' => ['placeholder' => 'Ex: Carrefoure Bellecoure'],
                'label' => 'Lieu de rendez-vous au départ avec les passagers',
                'required' => false
            ])
            ->add('rendezVsArrv', null, [
                'attr' => ['placeholder' => 'Ex: Métro Arènes (Facultatif)'],
                'label' => 'Lieu de dépôt des passagers à l\'arrivée',
                'required' => false
            ])
            ->add('description', null, [
                'label' => 'Description de votre déplacement',
                'data' => 'Ex: Je pars dans le sud, j\'ai peu de place dans le coffre...
                Chaque passager devra avoir un bagage moyen',
                'required' => false
            ])
            ->add('restrictions', null, [
                'label' => 'Restrictions lors du déplacement',
                'data' => 'Pas d\'animaux
                Pas de produits illicites
                Pas d\'objet ne pouvant suffire dans la voiture.',
                'required' => false
            ])
            ->add('marqVoiture', null, [
                'attr' => ['placeholder' => 'Ex: Renault 3000 (Facultatif)'],
                'label' => 'Marque de votre voiture',
                'required' => false
            ])
            ->add('email', null, [
                'label' => 'Votre email',
                'help' => 'Obligatoire pour recevoir les réservations des passagers!',
                'required' => false
            ])
            ->add('phone', null, [
                'attr' => ['placeholder' => 'Ex: 07532214 (Obligatoire)'],
                'label' => 'Numéro de téléphone',
                'help' => 'Obligatoire pour être contacté par les passagers!',
                'required' => false
            ])
            ->add('prenom', null, [
                'attr' => ['placeholder' => 'Ex: Kean (Facultatif)'],
                'label' => 'Prénom',
                'required' => false
            ])
            ->add('anneeNaiss', TextType::class, [
                'attr' => ['placeholder' => 'Ex: 1990 (Facultatif)'],
                'label' => 'Année de naissance',
                'mapped' => false,
                'required' => false
            ])
            ->add('codeUser', null, [
                'label' => 'code du trajet',
                'mapped' => false,
                'attr' => ['placeholder' => 'Ex: a4dd97eF'],
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
