<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Image;
use Vich\UploaderBundle\Form\Type\VichImageType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom', null, [
                'label' => 'Prénom',
                'help' => 'Pas obligatoire.',
                'required' => false
            ])
            ->add('email')
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrer votre mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit avoir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('phone', null, [
                'label' => 'Téléphone',
                'help' => 'Pas obligatoire',
                'required' => false
            ])
            ->add('anneeNaiss', null, [
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'yyyy',
                'attr' => ['placeholder' => '1999'],
                'label' => 'Année de naissance',
                'help' => 'Pas obligatoire',
                'required' => false
            ])
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
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'J\'accepte les conditions d\'utilisation.',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Accepter les conditions d\'utilisation.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
