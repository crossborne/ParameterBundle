<?php
/**
 * Created by JetBrains PhpStorm.
 * User: crossbone
 * Date: 4/14/13
 * Time: 7:50 PM
 * To change this template use File | Settings | File Templates.
 */

namespace crossborne\ParameterBundle\Services;


use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraints\Ip;
use crossborne\ParameterBundle\Entity\Parameter;
use crossborne\ParameterBundle\Entity\ParameterValue;
use crossborne\ParameterBundle\Model\IParametrized;
use crossborne\ParameterBundle\Model\ObjectParameterValue;
use crossborne\ParameterBundle\Model\ParameterCollection;

class ObjectParameterValueManager {

	/** @var \Doctrine\ORM\EntityManager */
	private $em;

	/** @var array */
	private $cachedValues = array();

	public function __construct(EntityManager $em) {
		$this->em = $em;
	}

	/**
	 * @return EntityManager
	 */
	public function getEntityManager() {
		return $this->em;
	}

	/**
	 * @param IParametrized $object
	 * @param null $rootId
	 * @param bool $emptyParams
	 * @param bool $recursive
	 * @return array
	 */
	public function findParamsForObject(IParametrized $object, $rootId = null) {

		// @todo: this shit needs to get optimized!!! a.k.a fast-coded

		$rootId = ($rootId === null ? $object->getRootParameterId() : $rootId);
		$query = $this->em->createQuery("
			SELECT p FROM crossborneParameterBundle:Parameter p
			WHERE p.parent " . ($rootId === null ? "IS NULL" : "= :parent") . "
			ORDER BY p.sort ASC
		");
		if ($rootId !== null)
			$query->setParameter("parent", $rootId);
		$query->useResultCache(true, 3600, 'parameters');
		$roots = $query->getResult();

		if (!$roots)
			return new ParameterCollection();

		if (!isset($this->cachedValues[$object->getId()])) {
			$this->cachedValues[$object->getId()] = $this->em->createQuery("
				SELECT v, p FROM crossborneParameterBundle:ParameterValue v
				JOIN v.parameter p
				WHERE v.targetGroupId = :groupId AND v.targetEntityId = :entityId
			")->setParameter("groupId", ParameterValue::generateTargetGroupId($object))
				->setParameter("entityId", $object->getId())
				->useResultCache(true, 3600, $this->getCacheKey($object))
				->getResult();
		}

		$finalArray = new ParameterCollection();
		foreach ($roots as $parameter) {
			$default = ParameterValue::createValueForObject($parameter, $object);
			$opv = new ObjectParameterValue($object, $this, $parameter, $default);
			$finalArray[$parameter->getId()] = $opv;
		}

		foreach ($this->cachedValues[$object->getId()] as $val) {
			if (isset($finalArray[$val->getParameter()->getId()])) {
				$finalArray[$val->getParameter()->getId()]->setValue($val);
				if ($val->getParameter()->getKey() !== null) {
					//$finalArray[$val->getParameter()->getKey()] = $finalArray[$val->getParameter()->getId()];
				}
			}
		}

		return $finalArray;
	}

	public function findValuesForParameter(Parameter $parameter, $word) {
		$p = new ParameterValue();
		$p->setValue("prdel");
		return array(
			$p, $p, $p
		);
	}

	private function getCacheKey(IParametrized $object) {
		return 'values' . $object->getId();
	}

	public function flushParameterValues(IParametrized $object) {
		$this->getEntityManager()->getConfiguration()->getResultCacheImpl()->delete($this->getCacheKey($object));
	}

	public function saveParameter(ObjectParameterValue $value) {
		$this->em->persist($value->getValue());
	}

	public function removeParameter(ObjectParameterValue $value) {
		$this->em->remove($value->getValue());
	}
}