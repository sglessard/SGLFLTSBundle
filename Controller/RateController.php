<?php

/*
 * This file is part of the SGLFLTSBundle package.
 *
 * (c) Simon Guillem-Lessard <s.g.lessard@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SGL\FLTSBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SGL\FLTSBundle\Entity\Rate;
use SGL\FLTSBundle\Form\RateType;

/**
 * Rate controller.
 *
 * @Route("/")
 */
class RateController extends Controller
{
    /**
     * Lists all Rate entities.
     *
     * @Route("/", name="sgl_flts_rate")
     * @Template("SGLFLTSBundle:Rate:List/index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SGLFLTSBundle:Rate')->retrieve();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Rate entity.
     *
     * @Route("/{id}/show", name="sgl_flts_rate_show")
     * @Template("SGLFLTSBundle:Rate:Crud/show.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SGLFLTSBundle:Rate')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rate entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Rate entity.
     *
     * @Route("/new", name="sgl_flts_rate_new")
     * @Template("SGLFLTSBundle:Rate:Crud/new.html.twig")
     */
    public function newAction()
    {
        $entity = new Rate();
        $form   = $this->createForm(new RateType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Rate entity.
     *
     * @Route("/create", name="sgl_flts_rate_create")
     * @Method("POST")
     * @Template("SGLFLTSBundle:Rate:Crud/new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Rate();
        $form = $this->createForm(new RateType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('sgl_flts_rate_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Rate entity.
     *
     * @Route("/{id}/edit", name="sgl_flts_rate_edit")
     * @Template("SGLFLTSBundle:Rate:Crud/edit.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SGLFLTSBundle:Rate')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rate entity.');
        }

        $editForm = $this->createForm(new RateType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Rate entity.
     *
     * @Route("/{id}/update", name="sgl_flts_rate_update")
     * @Method("POST")
     * @Template("SGLFLTSBundle:Rate:Crud/edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SGLFLTSBundle:Rate')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rate entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new RateType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('sgl_flts_rate_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Rate entity.
     *
     * @Route("/{id}/delete", name="sgl_flts_rate_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SGLFLTSBundle:Rate')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Rate entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sgl_flts_rate'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
