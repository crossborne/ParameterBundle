<?php

namespace crossborne\ParameterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use crossborne\ParameterBundle\Entity\Parameter;

class ParameterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('parent')
            ->add('caption')
			->add('key')
            ->add('type', 'choice', array(
				'choices' => array(
					null,
					Parameter::TYPE_NUMBER => "number",
					Parameter::TYPE_STRING => "string",
					Parameter::TYPE_ENUM => "enum",
					Parameter::TYPE_BOOLEAN => "boolean",
					Parameter::TYPE_TAGS => "tags"
				)
			))
			->add('required')
            ->add('units')
            ->add('sort')
			->add('formHelpers')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'crossborne\ParameterBundle\Entity\Parameter'
        ));
    }

    public function getName()
    {
        return 'crossborne_parameterbundle_parametertype';
    }
}
