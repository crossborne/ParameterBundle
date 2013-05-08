<?php
namespace crossborne\ParameterBundle\Form;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Exception\Exception;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use crossborne\ParameterBundle\Model\IParametrized;
use crossborne\ParameterBundle\Services\ObjectParameterValueManager;

/**
 * Created by JetBrains PhpStorm.
 * User: crossbone
 * Date: 4/11/13
 * Time: 11:47 PM
 * To change this template use File | Settings | File Templates.
 */
class ParametersSubscriber implements EventSubscriberInterface {

	/** @var \crossborne\ParameterBundle\Services\ObjectParameterValueManager */
	protected $pm;

	/** @var Router */
	protected $router;

	public function __construct(ObjectParameterValueManager $pm, Router $router) {
		$this->pm = $pm;
		$this->router = $router;
	}

	public static function getSubscribedEvents() {
		return array(
			FormEvents::PRE_SET_DATA => 'preSetData'
		);
	}

	public function preSetData(FormEvent $event) {
		$form = $event->getForm();
		$data = $event->getData();

		if (!$data)
			throw new Exception('The data provided is null, it must be object implementing IParametrized interface');

		if (!($data instanceof IParametrized))
			throw new Exception('The entity ' . get_class($data) . ' must implement IParametrized interface');

		$vals = $this->pm->findParamsForObject($data, null);
		$data->setParameters($vals);

		$form->add($data->getParametersPropertyName(), 'crossborne_parameterbundle_parameters_collection', array(
			'type' => new ObjectParameterValueType($data, $this->router),
			'label' => ' ',
		));
	}
}