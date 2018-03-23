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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SGL\FLTSBundle\Entity\Rate;
use SGL\FLTSBundle\Form\RateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

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
     * @return array
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
     * @param int $id
     * @return array
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
     * @return array
     * 
     * @Route("/new", name="sgl_flts_rate_new")
     * @Template("SGLFLTSBundle:Rate:Crud/new.html.twig")
     */
    public function newAction()
    {
        $entity = new Rate();
        $form   = $this->createForm(RateType::class, $entity, [
            'action' => $this->generateUrl('sgl_flts_rate_create')
        ]);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Rate entity.
     *
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * 
     * @Route("/create", name="sgl_flts_rate_create")
     * @Method("POST")
     * @Template("SGLFLTSBundle:Rate:Crud/new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Rate();
        $form = $this->createForm(RateType::class, $entity, [
            'action' => $this->generateUrl('sgl_flts_rate_create')
        ]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Created!'
            );

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
     * @param int $id
     * @return array 
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

        $editForm = $this->createForm(RateType::class, $entity, [
            'action' => $this->generateUrl('sgl_flts_rate_update', ['id' => $id])
        ]);
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
     * @param Request $request
     * @param int $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
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
        $editForm = $this->createForm(RateType::class, $entity, [
            'action' => $this->generateUrl('sgl_flts_rate_update', ['id' => $id])
        ]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Updated!'
            );

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
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * 
     * @Route("/{id}/delete", name="sgl_flts_rate_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SGLFLTSBundle:Rate')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Rate entity.');
            }

            $em->remove($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Deleted!'
            );
        }

        return $this->redirect($this->generateUrl('sgl_flts_rate'));
    }

     /**
      * Get Part's rate
      *
      * @param Request $request
      * @return Response (json)
      * 
      * @Route("/part-rate/", name="sgl_flts_part_rate")
      * @Method("POST")
      */
    public function getRateAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $data = $request->request->get('part_id');

        $part = $em->getRepository('SGLFLTSBundle:Part')->find(intval($data));
        if (!$part) {
            throw $this->createNotFoundException('Unable to find Part entity.');
        }

        $response = new Response(json_encode(array(
            'rate_id' => $part->getProject()->getClient()->getRate()->getId()
        )));

        return $response;
    }

    /**
     * @param $id
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', HiddenType::class)
            ->getForm()
        ;
    }
}
