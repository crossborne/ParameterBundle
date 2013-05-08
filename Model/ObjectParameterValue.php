<?php
namespace crossborne\ParameterBundle\Model;
use crossborne\ParameterBundle\Entity\Parameter;
use crossborne\ParameterBundle\Entity\ParameterValue;
use crossborne\ParameterBundle\Services\ObjectParameterValueManager;

/**
 * Created by JetBrains PhpStorm.
 * User: crossbone
 * Date: 4/13/13
 * Time: 3:17 PM
 * To change this template use File | Settings | File Templates.
 */
class ObjectParameterValue {

	/** @var \crossborne\ParameterBundle\Entity\Parameter */
	private $parameter;

	/** @var \crossborne\ParameterBundle\Entity\ParameterValue */
	private $value;

	private $children = null;

	/** @var \crossborne\ParameterBundle\Model\ObjectParameterValue */
	private $object;

	/** @var \crossborne\ParameterBundle\Services\ObjectParameterValueManager */
	private $manager;

	public function __construct(IParametrized $object, ObjectParameterValueManager $manager, Parameter $parameter, ParameterValue $value = null) {
		$this->object = $object;
		$this->manager = $manager;
		$this->parameter = $parameter;
		$this->value = $value;
	}

	public function getChildren() {
		if ($this->children === null)
			$this->children = $this->manager->findParamsForObject($this->object, $this->getParameter()->getId());
		return $this->children;
	}

	public function setChildren(array $children) {
		$this->children = $children;
	}

	public function hasChildren() {
		return (bool) count($this->getChildren());
	}

	/**
	 * @return Parameter
	 */
	public function getParameter() {
		return $this->parameter;
	}

	/**
	 * @param Parameter $parameter
	 */
	public function setParameter(Parameter $parameter) {
		$this->parameter = $parameter;
	}

	/**
	 * @return ParameterValue
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @param ParameterValue $value
	 */
	public function setValue(ParameterValue $value) {
		$this->value = $value;
	}
}