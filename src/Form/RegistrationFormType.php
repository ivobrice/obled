<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Image;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\PasswordStrength;

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
            ->add('email', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrer votre adresse email.'
                    ])
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'options' => [
                    'attr' => [
                        'autocomplete' => 'new-password',
                        // 'class' => 'col-md-6',
                    ],
                ],
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Entrer le mot de passe.',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Votre mot de passe doit avoir au moins {{ limit }} caractères.',
                            'max' => 4096,
                        ]),
                        // new PasswordStrength(),
                        // new NotCompromisedPassword(),
                    ],
                    'label' => 'Mot de passe',
                ],
                'second_options' => [
                    'label' => 'Répétez le mot de passe',
                ],
                'invalid_message' => 'Les mots de passe doivent être identiques',
                'mapped' => false,
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
