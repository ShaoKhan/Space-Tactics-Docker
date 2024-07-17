<?php

namespace App\Form;

use App\Entity\Alliance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;
use Vich\UploaderBundle\Form\Type\VichImageType;


class AllianceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'slug', HiddenType::class,
                [
                    'data' => $options['uuid'],
                ],
            )
            ->add(
                'name', TextType::class,
                [
                    'label' => 'Allianzname',
                    'attr'  => [
                        'class' => 'form-control',
                    ],
                ],
            )
            ->add(
                'allianceTag', TextType::class,
                [
                    'label'       => 'Allianztag',
                    'attr'        => [
                        'class' => 'form-control',
                    ],
                    'constraints' => [
                        new Length(['max' => 5, 'maxMessage' => 'Der Tag darf maximal {{ limit }} Zeichen lang sein.']),
                    ],
                ],
            )
            ->add(
                'headline', TextType::class,
                [
                    'label'    => 'Überschrift',
                    'attr'     => [
                        'class' => 'form-control',
                    ],
                    'required' => FALSE,
                ],
            )
            ->add(
                'description', TextareaType::class,
                [
                    'label'    => 'Beschreibung',
                    'attr'     => [
                        'class' => 'form-control',
                        'rows'  => '5',
                    ],
                    'required' => FALSE,
                ],
            )
            ->add(
                'url', UrlType::class,
                [
                    'label'       => 'Webseite',
                    'attr'        => [
                        'class' => 'form-control',
                    ],
                    'required'    => FALSE,
                    'constraints' => [
                        new Assert\Url(['message' => 'Die URL ist ungültig.']),
                    ],

                ],
            )
            ->add('logo', VichImageType::class, [
                'label'          => 'Logo',
                'allow_delete'   => FALSE,
                'delete_label'   => FALSE,
                'download_uri'   => FALSE,
                'download_label' => FALSE,
                'asset_helper'   => FALSE,
                'attr'           => [
                    'class' => 'form-control',
                ],
                'required'       => FALSE,
            ])
            ->add(
                'save', SubmitType::class, [
                'label' => 'Allianz gründen',
                'attr'  => [
                    'class' => 'btn btn-primary mt-2',
                ],
            ],
            );
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Alliance::class,
                'uuid'       => NULL,
            ],
        );
    }
}
