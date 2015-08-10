<?php

namespace AdminBundle\Form\Slide;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotNull;

class SlideType extends AbstractType
{
    private $languages;
    private $mode;
    
    public function __construct(array $languages, $mode = "create")
    {
        $this->languages = $languages;
        $this->mode = $mode;
    }
    
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
        if ($options['mode'] == 'create') {
            $imageConstraints[] = new NotNull();
        }

        $builder
            ->add('published')
            ->add('slug')
            ->add('image', 'file', array(
                'mapped' => false,
                'constraints' => $imageConstraints,
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
            'mode' => $this->mode,
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
