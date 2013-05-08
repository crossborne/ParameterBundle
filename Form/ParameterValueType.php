<?php

namespace crossborne\ParameterBundle\Form;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ParameterValueType extends AbstractType
{
	/** @var \Symfony\Bundle\FrameworkBundle\Routing\Router */
	private $router;

	public function __construct(Router $router) {
		$this->router = $router;
	}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /*$builder
            ->add('value', 'text', array(
				'label' => ' '
			))
        ;*/
		$builder->addEventSubscriber(new AddFieldByTypeSubscriber($this->router));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'crossborne\ParameterBundle\Entity\ParameterValue'
        ));
    }

    public function getName()
    {
        return 'crossborne_parameterbundle_parametervaluetype';
    }
}
