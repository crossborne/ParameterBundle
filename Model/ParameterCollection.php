<?php
/**
 * Created by JetBrains PhpStorm.
 * User: crossbone
 * Date: 4/16/13
 * Time: 12:28 PM
 * To change this template use File | Settings | File Templates.
 */

namespace crossborne\ParameterBundle\Model;


use Doctrine\Common\Collections\ArrayCollection;

class ParameterCollection extends ArrayCollection {

	public function getByKey($key) {
		foreach ($this->getValues() as $o) {
			if ($key === $o->getParameter()->getKey())
				return $o;
		}
		return null;
	}

}