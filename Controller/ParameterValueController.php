<?php

namespace crossborne\ParameterBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use crossborne\ParameterBundle\Entity\ParameterValue;
use crossborne\ParameterBundle\Form\ParameterValueType;

/**
 * ParameterValue controller.
 *
 */
class ParameterValueController extends Controller
{
    /**
     * Lists all ParameterValue entities.
     *
     */
    public function indexAction($id, $search)
    {
        $em = $this->getDoctrine()->getManager();

		$result = $em->getRepository("crossborneParameterBundle:ParameterValue")->createQueryBuilder('p')
			->where('p.parameter = :id')
			->andWhere('p.value LIKE :value')
			->groupBy('p.value')
			->setParameter('id', $id)
			->setParameter('value', $search . "%")
			->getQuery()
			->getResult();

		$out = array();
		foreach($result as $e) {
			if ($e->getValue() != null)
				$out[] = $e->getValue();
		}

		$response = new JsonResponse($out);

        return $response;
	}
}
