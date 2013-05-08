<?php
namespace crossborne\ParameterBundle\Model;

use crossborne\ParameterBundle\Model\ParameterCollection;

/**
 * Created by JetBrains PhpStorm.
 * User: crossbone
 * Date: 4/11/13
 * Time: 8:20 PM
 * To change this template use File | Settings | File Templates.
 */
interface IParametrized {

	/**
	 * Self explanatory
	 * @return integer
	 */
	public function getId();

	/**
	 * Should return id of root parameter or null for all roots
	 * @return integer
	 */
	public function getRootParameterId();

	/**
	 * Should set given array to the desired property
	 *
	 * @param ParameterCollection $parameters
	 */
	public function setParameters(ParameterCollection $parameters);

	/**
	 * Should return property value
	 * @return ParameterCollection
	 */
	public function getParameters();

	/**
	 * Should return string with property name ex.: 'parameters'
	 * @return string
	 */
	public function getParametersPropertyName();
}