<?php

namespace ActeurBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use FOS\UserBundle\Form\Type\RegistrationFormType as  BaseRegistrationFormType;

class PorteurType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom',null,[
            'attr' => [
                'placeholder'  => 'Saissez votre nom',

            ]

        ])
            ->add('prenom',null,[
                'attr' => [
                    'placeholder'  => 'Saissez votre prenom(s)',

                ]
            ])
            ->add('dateNaissance',null,[
                'attr' => [
                    'placeholder'  => 'Saissez votre date de naissance ',

                ]
            ])
            ->add('lieuNaissance',null,[
                'attr' => [
                    'placeholder'  => 'Saissez votre lieu de naissance ',

                ]
            ])
            ->add('pays',null,[
                'attr' => [
                    'placeholder'  => 'Selectionnez votre pays',

                ]
            ])
            ->add('ville',null,[
                'attr' => [
                    'placeholder'  => 'Selectionnez votre ville',

                ]
            ])
            ->add('adresse',null,[
                'attr' => [
                    'placeholder'  => 'Saissez votre adresse ',

                ]
            ])

            ->add('telephone',null, [
                'attr' => [
                    'placeholder'  => 'Saissez le numero de telephone',

                ]
            ])
            ->add('raisonSociale',null,[
                'attr' => [
                    'placeholder'  => 'Saissez la raison sociale',

                ]
            ])
            ->add('imageFile',VichImageType::class)
            ->add('imageFileCni',VichImageType::class)
            ->add('nature',EntityType::class, [
                'class' => 'AdminBundle\Entity\Nature',
                'choice_label'=>'type',
                'placeholder'=>'Selectionez Personne physique ou morale']);
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AdminBundle\Entity\Porteur'
        ));
    }

//    /**
//     * {@inheritdoc}
//     */
//    public function getBlockPrefix()
//    {
//        return 'acteurbundle_porteur';
//    }


}
