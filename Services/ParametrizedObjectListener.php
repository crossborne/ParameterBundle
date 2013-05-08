<?php
/**
 * Created by JetBrains PhpStorm.
 * User: crossbone
 * Date: 4/14/13
 * Time: 5:40 PM
 * To change this template use File | Settings | File Templates.
 */

namespace crossborne\ParameterBundle\Services;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Symfony\Component\DependencyInjection\Container;
use crossborne\ParameterBundle\Model\IParametrized;
use crossborne\ParameterBundle\Model\ParameterCollection;

class ParametrizedObjectListener implements EventSubscriber {

	/**
	 * @var \Symfony\Component\DependencyInjection\Container
	 */
	protected $container;

	public function __construct(Container $container) {
		//$this->pm = $container->get('crossborne_parameter.manager'); // tohle nefunguje protoze to rve na circular reference exception
		$this->container = $container;
	}

	/**
	 * @return \crossborne\ParameterBundle\Services\ObjectParameterValueManager
	 */
	public function getManager() {
		return $this->container->get('crossborne_parameter.manager');
	}

	/**
	 * Returns an array of events this subscriber wants to listen to.
	 *
	 * @return array
	 */
	function getSubscribedEvents()
	{
		return array(
			Events::postLoad,
			Events::loadClassMetadata,
			Events::postPersist,
			Events::postUpdate,
			Events::preRemove,
			Events::onFlush
		);
	}

	public function postLoad(LifecycleEventArgs $args) {
		if ($args->getEntity() instanceof IParametrized) {
			$params = $this->getManager()->findParamsForObject($args->getEntity());
			$args->getEntity()->setParameters($params);
		}
	}

	public function loadClassMetadata(LoadClassMetadataEventArgs $args) {
		return;
		$a = $args->getClassMetadata();
		var_dump($a);
		exit;
	}

	public function postPersist(LifecycleEventArgs $args) {
		if ($args->getEntity() instanceof IParametrized) {
			$this->updateParameters($args->getEntity());
		}
	}

	public function postUpdate(LifecycleEventArgs $args) {
		if ($args->getEntity() instanceof IParametrized) {
			$this->updateParameters($args->getEntity());
		}
	}

	public function preRemove(LifecycleEventArgs $args) {
		if ($args->getEntity() instanceof IParametrized) {
			$this->removeParametersRecursively($args->getEntity(), $args->getEntity()->getParameters());
			$this->getManager()->getEntityManager()->flush();
		}
	}

	public function onFlush(OnFlushEventArgs $args) {

	}

	public function updateParameters(IParametrized $entity) {
		$parameters = $entity->getParameters();
		$this->updateParametersRecursively($entity, $parameters);
		$this->getManager()->getEntityManager()->flush();
		$this->getManager()->flushParameterValues($entity);
	}

	private function updateParametersRecursively(IParametrized $entity, ParameterCollection $parameters) {
		foreach ($parameters as $p) {
			$p->getValue()->setTargetEntityId($entity->getId());
			$this->updateParametersRecursively($entity, $p->getChildren());
			$this->getManager()->saveParameter($p);
		}
	}

	private function removeParametersRecursively(IParametrized $entity, ParameterCollection $parameters) {
		foreach ($parameters as $p) {
			$this->removeParametersRecursively($entity, $p->getChildren());
			$this->getManager()->removeParameter($p);
		}
	}
}