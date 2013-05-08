<?php

namespace crossborne\ParameterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FilterController extends Controller
{
    public function showAction($parentId)
    {
		$pp = $this->getDoctrine()->getRepository("crossborneParameterBundle:Parameter")->findBy(array('parent' => $parentId), array('sort' => 'ASC'));

		$byIds = array();
		foreach ($pp as $p) {
			$byIds[$p->getId()] = $p;
		}

		$vals = $this->getDoctrine()->getRepository("crossborneParameterBundle:ParameterValue")->findBy(array(), array('value' => 'ASC'));
		foreach ($vals as $v) {
			if (isset($byIds[$v->getParameter()->getId()]))
				$byIds[$v->getParameter()->getId()]->addValue($v);
		}

        return $this->render('crossborneParameterBundle:Filter:show.html.twig', array('parameters' => $byIds));
    }
}
