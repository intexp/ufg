<?php

namespace AdminBundle\Form\Company;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CompanyType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $imageConstraints = array();
        $imageConstraints[] = new File(
            array(
                'maxSize' => '8M',
                'mimeTypes' => array(
                    'image/gif',
                    'image/jpeg',
                    'image/png',
                ),
                'mimeTypesMessage' => 'The file type is invalid. Allowed file types are: gif, jpeg, png.',
            )
        );

        $builder
            ->add('slug')
            ->add('website')
            ->add('phone')
            ->add('email')
            ->add('skype')
            ->add('facebook')
            ->add('twitter')
            ->add('linkedin')
            ->add('published')
            ->add('image', 'file', array(
                'mapped' => false,
                'constraints' => $imageConstraints,
            ))
            ->add('translations', 'a2lix_translations', array(
                'required' => false,
                'fields' => array(
                    'title' => array(
                        'attr' => array(
                            'class' => 'form-control',
                        ),
                    ),
                    'description' => array(
                        'attr' => array(
                            'class' => 'form-control ckeditor',
                        ),
                    ),
                    'address' => array(
                        'attr' => array(
                            'class' => 'form-control',
                        ),
                    ),
                    'street' => array(
                        'attr' => array(
                            'class' => 'form-control',
                        ),
                    ),
                ),
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Company',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'company';
    }
}
