<?php

namespace ActeurBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class InscriptionAttenteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom')
            ->add('prenom')
            ->add('email', EmailType::class)
            ->add('telephone')
            ->add('token', HiddenType::class, [
                'constraints' => [new NotBlank()],
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)

    {
        $resolver->setDefaults(array(
            'data_class' => 'ActeurBundle\Entity\InscriptionAttente'
        ));

    }

    public function getBlockPrefix()
    {
        return 'acteur_bundle_inscription_attente';
    }


}
