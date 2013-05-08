<?php

namespace crossborne\ParameterBundle\Form;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use crossborne\ParameterBundle\Model\IParametrized;

class ObjectParameterValueType extends AbstractType
{

	/** @var \crossborne\ParameterBundle\Form\IParametrized */
	private $object;

	/** @var Router */
	private $router;

	public function __construct(IParametrized $object, Router $router) {
		$this->object = $object;
		$this->router = $router;
	}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber(new AddParamFieldSubscriber($this->object, $this->router));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'crossborne\ParameterBundle\Model\ObjectParameterValue'
        ));
    }

    public function getName()
    {
        return 'crossborne_parameterbundle_objectparametervaluetype';
    }
}
