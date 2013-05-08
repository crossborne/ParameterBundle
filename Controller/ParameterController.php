<?php

namespace crossborne\ParameterBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use crossborne\ParameterBundle\Entity\Parameter;
use crossborne\ParameterBundle\Form\ParameterType;

/**
 * Parameter controller.
 *
 */
class ParameterController extends Controller
{
    /**
     * Lists all Parameter entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('crossborneParameterBundle:Parameter')->findAll();

        return $this->render('crossborneParameterBundle:Parameter:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Parameter entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Parameter();
        $form = $this->createForm(new ParameterType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('parameter_show', array('id' => $entity->getId())));
        }

        return $this->render('crossborneParameterBundle:Parameter:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Parameter entity.
     *
     */
    public function newAction()
    {
        $entity = new Parameter();
        $form   = $this->createForm(new ParameterType(), $entity);

        return $this->render('crossborneParameterBundle:Parameter:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Parameter entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('crossborneParameterBundle:Parameter')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Parameter entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('crossborneParameterBundle:Parameter:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Parameter entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('crossborneParameterBundle:Parameter')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Parameter entity.');
        }

        $editForm = $this->createForm(new ParameterType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('crossborneParameterBundle:Parameter:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Parameter entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('crossborneParameterBundle:Parameter')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Parameter entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ParameterType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('parameter_edit', array('id' => $id)));
        }

        return $this->render('crossborneParameterBundle:Parameter:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Parameter entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('crossborneParameterBundle:Parameter')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Parameter entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('parameter'));
    }

    /**
     * Creates a form to delete a Parameter entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
