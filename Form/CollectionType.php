<?php
/**
 * Created by JetBrains PhpStorm.
 * User: crossbone
 * Date: 4/15/13
 * Time: 1:21 AM
 * To change this template use File | Settings | File Templates.
 */

namespace crossborne\ParameterBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\CollectionType as BaseCollection;

class CollectionType extends BaseCollection {

	public function getName() {
		return 'crossborne_parameterbundle_parameters_collection';
	}
}