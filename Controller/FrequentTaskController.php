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
use SGL\FLTSBundle\Entity\FrequentTask;
use SGL\FLTSBundle\Form\FrequentTaskType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

/**
 * FrequentTask controller.
 *
 * @Route("/")
 */
class FrequentTaskController extends Controller
{
    /**
     * Lists all FrequentTask entities.
     *
     * @Route("/", name="sgl_flts_frequenttask")
     * @Template("SGLFLTSBundle:FrequentTask:List/index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SGLFLTSBundle:FrequentTask')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a FrequentTask entity.
     *
     * @param int $id
     * @return array
     * 
     * @Route("/{id}/show", name="sgl_flts_frequenttask_show")
     * @Template("SGLFLTSBundle:FrequentTask:Crud/show.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SGLFLTSBundle:FrequentTask')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FrequentTask entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new FrequentTask entity.
     *
     * @return array
     * 
     * @Route("/new", name="sgl_flts_frequenttask_new")
     * @Template("SGLFLTSBundle:FrequentTask:Crud/new.html.twig")
     */
    public function newAction()
    {
        $entity = new FrequentTask();
        $form   = $this->createForm(FrequentTaskType::class, $entity, [
            'action' => $this->generateUrl('sgl_flts_frequenttask_create')
        ]);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new FrequentTask entity.
     *
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * 
     * @Route("/create", name="sgl_flts_frequenttask_create")
     * @Method("POST")
     * @Template("SGLFLTSBundle:FrequentTask:Crud/new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new FrequentTask();
        $form = $this->createForm(FrequentTaskType::class, $entity, [
            'action' => $this->generateUrl('sgl_flts_frequenttask_create')
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

            return $this->redirect($this->generateUrl('sgl_flts_frequenttask_edit', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing FrequentTask entity.
     *
     * @param int $id
     * @return array
     * 
     * @Route("/{id}/edit", name="sgl_flts_frequenttask_edit")
     * @Template("SGLFLTSBundle:FrequentTask:Crud/edit.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SGLFLTSBundle:FrequentTask')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FrequentTask entity.');
        }

        $editForm = $this->createForm(FrequentTaskType::class, $entity, [
            'action' => $this->generateUrl('sgl_flts_frequenttask_update', ['id' => $id])
        ]);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing FrequentTask entity.
     *
     * @param Request $request
     * @param int $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * 
     * @Route("/{id}/update", name="sgl_flts_frequenttask_update")
     * @Method("POST")
     * @Template("SGLFLTSBundle:FrequentTask:Crud/edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SGLFLTSBundle:FrequentTask')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FrequentTask entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(FrequentTaskType::class, $entity, [
            'action' => $this->generateUrl('sgl_flts_frequenttask_update', ['id' => $id])
        ]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Updated!'
            );

            return $this->redirect($this->generateUrl('sgl_flts_frequenttask_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a FrequentTask entity.
     *
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * 
     * @Route("/{id}/delete", name="sgl_flts_frequenttask_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SGLFLTSBundle:FrequentTask')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find FrequentTask entity.');
            }

            $em->remove($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Deleted!'
            );
        }

        return $this->redirect($this->generateUrl('sgl_flts_frequenttask'));
    }

    /**
     * @param $id
     *
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
