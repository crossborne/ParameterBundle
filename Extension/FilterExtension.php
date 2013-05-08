<?php
/**
 * Created by JetBrains PhpStorm.
 * User: crossbone
 * Date: 4/16/13
 * Time: 2:20 PM
 * To change this template use File | Settings | File Templates.
 */

namespace crossborne\ParameterBundle\Extension;



class FilterExtension extends \Twig_Extension {

	/**
	 * Returns a list of global variables to add to the existing list.
	 *
	 * @return array An array of global variables
	 */
	public function getGlobals()
	{
		return array('crossborne_parameterbundle_filter' => "XXX");
	}

	public function getName()
	{
		return 'crossborne_parameterbundle_filter_extension';
	}
}