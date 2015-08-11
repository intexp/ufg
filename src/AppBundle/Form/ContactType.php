<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('email')
            ->add('message', 'textarea')
            ->add('captcha', 'captcha', array(
                'width' => '100',
                'height' => '30',
                'length' => 4,
                'background_color' => array(229,229,229),
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $collectionConstraint = new Collection(array(
            'name' => array(
                new NotBlank(array('message' => 'name.not_blank')),
                new Length(array('min' => 2))
            ),
            'email' => array(
                new NotBlank(array('message' => 'email.not_blank')),
                new Email(array('message' => 'email.valid'))
            ),
            'message' => array(
                new NotBlank(array('message' => 'message.not_blank')),
                new Length(array('min' => 2, 'max' => 500)),
            )
        ));

        $resolver->setDefaults(array(
            'constraints' => $collectionConstraint,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'contact';
    }
}