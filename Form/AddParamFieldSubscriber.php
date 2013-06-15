<?php
namespace crossborne\ParameterBundle\Form;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Exception\Exception;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use crossborne\ParameterBundle\Entity\ParameterValue;
use crossborne\ParameterBundle\Model\IParametrized;
use crossborne\ParameterBundle\Model\ObjectParameterValue;

/**
 * Created by JetBrains PhpStorm.
 * User: crossbone
 * Date: 4/11/13
 * Time: 11:47 PM
 * To change this template use File | Settings | File Templates.
 */
class AddParamFieldSubscriber implements EventSubscriberInterface {

	/** @var \crossborne\ParameterBundle\Model\IParametrized */
	private $object;

	/** @var Router */
	private $router;

	public function __construct(IParametrized $object, Router $router) {
		$this->object = $object;
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

		$form->add('value', new ParameterValueType($this->router), array(
			'label' => ' ', //$data->getParameter()->getCaption(),
			'required' => $data->getParameter()->isRequired()
		));

		$form->add('children', 'crossborne_parameterbundle_parameters_collection', array(
			'type' => new ObjectParameterValueType($this->object, $this->router),
			'label' => ' '
		));
	}
}