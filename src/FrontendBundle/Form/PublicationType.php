<?php

namespace FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PublicationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('contenu',ChoiceType::class,[
                'choices' =>
                [
                    'image' => 'image',
                    'video' => 'video'

                ]
            ])
            ->add('description')
            ->add('categorie',ChoiceType::class,[
                'choices' =>
                    [
                        'musique' => 'musique',
                        'peinture' => 'peinture',
                        'dance' => 'dance'

                    ]
            ])
            ->add('fileUpload', FileType::class, [
                'label' => 'image',
                'mapped' => false,
                'required' => false,
                ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Publication'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_publication';
    }


}
