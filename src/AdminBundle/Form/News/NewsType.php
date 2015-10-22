<?php

namespace AdminBundle\Form\News;

use AdminBundle\Form\DataTransformer\SlugTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\File;

class NewsType extends AbstractType
{
    private $languages;
    private $mode;

    public function __construct(array $languages, $mode = "create")
    {
        $this->languages = $languages;
        $this->mode = $mode;
    }

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
            ->add('date', 'collot_datetime', array(
                    'pickerOptions' => array(
                        'format' => 'dd/mm/yyyy',
                        'autoclose' => true,
                        'minView' => 'month',
                    ),
                    'data' => new \DateTime(),
                )
            )
            ->add('published')
            ->add('images', 'file', array(
                'multiple' => true,
                'mapped' => false,
//                'constraints' => $imageConstraints,
                'attr' => array(
                    'multiple' => true,
                ),
            ))
            ->add('translations', 'a2lix_translations', array(
                'required' => false,
                'locales' => $options['languages'],
                'label' => false,
                'fields' => array(
                    'title' => array(
                        'required' => false,
                        'attr' => array(
                            'class' => 'form-control',
                        ),
                    ),
                    'shortDescription' => array(
                        'required' => false,
                        'attr' => array(
                            'class' => 'form-control ckeditor',
                            'rows' => 3,
                        )
                    ),
                    'body' => array(
                        'required' => false,
                        'attr' => array(
                            'class' => 'form-control ckeditor',
                        ),
                    ),
                ),
            ))
        ;

        $builder->get('slug')
            ->addModelTransformer(new SlugTransformer())
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\News',
            'languages' => $this->languages,
            'mode' => $this->mode,
        ));
    }

    public function getName()
    {
        return 'admin_news';
    }
}
