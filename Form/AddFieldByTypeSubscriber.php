<?php
namespace crossborne\ParameterBundle\Form;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Exception\Exception;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use crossborne\ParameterBundle\Entity\ParameterValue;
use crossborne\ParameterBundle\Entity\Parameter;

/**
 * Created by JetBrains PhpStorm.
 * User: crossbone
 * Date: 4/11/13
 * Time: 11:47 PM
 * To change this template use File | Settings | File Templates.
 */
class AddFieldByTypeSubscriber implements EventSubscriberInterface {

	const REPLACING_WORD = "__word__";

	/** @var \Symfony\Bundle\FrameworkBundle\Routing\Router */
	private $router;

	public function __construct(Router $router) {
		$this->router = $router;
	}

	public static function getSubscribedEvents() {
		return array(
			FormEvents::PRE_SET_DATA => 'preSetData',
			FormEvents::POST_BIND => 'postBind'
		);
	}

	public function preSetData(FormEvent $event) {
		$form = $event->getForm();
		$data = $event->getData();

		switch($data->getParameter()->getType()) {
			case Parameter::TYPE_NUMBER:
				$form->add('value', 'number', array(
					'label' => $data->getParameter()->getCaption() . $data->getParameter()->getFormHelpers(),
					'required' => $data->getParameter()->isRequired(),
					'attr' => array('append_input' => $data->getParameter()->getUnits(), 'class' => 'span1')
				));
				break;
			case Parameter::TYPE_STRING:
				$form->add('value', 'text', array(
					'label' => $data->getParameter()->getCaption() . $data->getParameter()->getFormHelpers(),
					'required' => $data->getParameter()->isRequired(),
					'attr' => array(
						'class' => 'span2',
						'data-parameterCallback' => $this->router->generate("parametervalue", array(
							'id' => $data->getParameter()->getId(),
							'search' => self::REPLACING_WORD
						))
					),
				));
				break;
			case Parameter::TYPE_ENUM:
				$form->add('value', 'choice', array(
					'label' => $data->getParameter()->getCaption(),
					'choices' => $data->getParameter()->getChoices(),
					'expanded' => (count($data->getParameter()->getChoices()) > 2) ? false : false,
					'attr' => (count($data->getParameter()->getChoices()) > 2) ? array('class' => 'span2') : array(),
					'multiple' => false,
					'required' => $data->getParameter()->isRequired(),
					'empty_value' => $data->getParameter()->isRequired() ? 'Vybrat' : false
				));
				break;
			case Parameter::TYPE_BOOLEAN:
				$form->add('value', 'checkbox', array(
					'label' => $data->getParameter()->getCaption(),
					'required' => $data->getParameter()->isRequired(),
					'data' => (bool) $data->getValue()
				));
				break;
			case Parameter::TYPE_TAGS:
				$form->add('value', 'textarea', array(
					'label' => $data->getParameter()->getCaption(),
					'required' => $data->getParameter()->isRequired(),
				));
				break;
			default:
				$form->add('value', 'text', array(
					'label' => $data->getParameter()->getCaption() . ' _ DEFAULT',
					'required' => $data->getParameter()->isRequired()
				));
				break;
		}
	}

	public function postBind(FormEvent $event) {
		//$event->getForm()->addError(new FormError("aaa"));
	}
}