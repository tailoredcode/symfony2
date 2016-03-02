<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Inject;
use Symfony\Component\Validator\Constraints;



class NewsletterForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            -> add('email', null, array(
                'label' => 'newsletter.email',
                'constraints' => array(
                    new Constraints\Email(array(
                        'message' => 'This is not valid email address'
                    )),
                    new Constraints\NotNull(array(
                        'message' => 'Email address cannot be empty'
                    ))
                )
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Newsletter',
            'csrf_protection' => false,
            'translation_domain' => 'messages',
            'validation_groups' => array(
                'Default'
            )
        ));
    }

    public function getName()
    {
        return '';
    }

}

