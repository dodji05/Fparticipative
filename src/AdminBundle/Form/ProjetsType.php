<?php

namespace AdminBundle\Form;

use AdminBundle\Entity\media;
use AdminBundle\Form\Type\MediaType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProjetsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nomProjet')
            ->add('descriptionProjet')
            ->add('descriptionCourteProjet')
            ->add('categorie', EntityType::class, [
                'class' => 'AdminBundle\Entity\Categories',
                'choice_label' => 'nomCategorie',
                'placeholder'=>'Selectionnez la catégorie',
                'label'=>'Categorie'
            ])
            ->add('imageFile', VichImageType::class,[
                'label'=>'logo du projet'
            ])
            ->add('media', CollectionType::class, [
                'entry_type' => MediaType::class,
                'allow_add'  => true,
                'label'=>'photo'

            ])
            ->add('porteur', EntityType::class, [
                'class' => 'AdminBundle\Entity\Porteur',
                'choice_label' => 'nom',
                'placeholder'=>'Veuillez selectionner le porteur du projet']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AdminBundle\Entity\Projets'
        ));

    }

    public function getBlockPrefix()
    {
        return 'admin_bundleprojets';
    }
}
