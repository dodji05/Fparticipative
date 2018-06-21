<?php

namespace AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PorteurType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom',null,[
            'attr' => [
                'placeholder'  => 'Saissez le nom du porteur du projet',

            ]

        ])
            ->add('prenom',null,[
                'attr' => [
                    'placeholder'  => 'Saissez le prenom du porteur du projet',

                ]
            ])
            ->add('adresse',null,[
                'attr' => [
                    'placeholder'  => 'Saissez l\'adresse du porteur de projets ',

                ]
            ])
            ->add('mail',EmailType::class,[
                'attr' => [
                    'placeholder'  => 'Saissez le mail du porteur de projet',

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
            ->add('type',EntityType::class, [
                'class' => 'AdminBundle\Entity\Type',
                'choice_label'=>'type',
                'placeholder'=>'Selectionez Personne physique ou morale']);
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

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'adminbundle_porteur';
    }


}
