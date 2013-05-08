<?php
/**
 * Created by JetBrains PhpStorm.
 * User: crossbone
 * Date: 4/16/13
 * Time: 7:21 PM
 * To change this template use File | Settings | File Templates.
 */

namespace crossborne\ParameterBundle\Model;


interface IParametrizedRepository {

	public function getAllIdsToCompare();

	public function intersectWithIds(array $ids);
}