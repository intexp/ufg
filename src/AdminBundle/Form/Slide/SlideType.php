<?php

namespace AdminBundle\Form\Slide;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SlideType extends AbstractType
{
    private $languages;
    
    public function __construct(array $languages)
    {
        $this->languages = $languages;
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('published')
            ->add('slug')
            ->add('image', 'file', array(
                "mapped" => false,
            ))
            ->add('translations', 'a2lix_translations', array(
                'label' => false,
                'locales' => $options['languages'],
                'required' => false,
                'fields' => array(
                    'title' => array(
                        'required' => false,
                        'attr' => array(
                            'class' => 'form-control',
                        )
                    ),
                    'description' => array(
                        'required' => false,
                        'attr' => array(
                            'class' => 'form-control ckeditor',
                        )
                    ),
                )
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Slide',
            'languages' => $this->languages,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'slide';
    }
}
