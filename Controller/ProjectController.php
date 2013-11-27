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
use SGL\FLTSBundle\Entity\Project;
use SGL\FLTSBundle\Form\ProjectType;

/**
 * Project controller.
 *
 * @Route("/")
 */
class ProjectController extends Controller
{
    /**
     * Lists all Project entities.
     *
     * @Route("/", name="sgl_flts_project")
     * @Template("SGLFLTSBundle:Project:List/index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $clients = $em->getRepository('SGLFLTSBundle:Client')->retrieveWithPartsProjects();

        return array(
            'clients' => $clients,
        );
    }

    /**
     * Finds and displays a Project entity.
     *
     * @Route("/{id}/show", name="sgl_flts_project_show")
     * @Template("SGLFLTSBundle:Project:Crud/show.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SGLFLTSBundle:Project')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Project entity.
     *
     * @Route("/new", name="sgl_flts_project_new")
     * @Template("SGLFLTSBundle:Project:Crud/new.html.twig")
     */
    public function newAction()
    {
        $entity = new Project();
        $form   = $this->createForm(new ProjectType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Project entity.
     *
     * @Route("/create", name="sgl_flts_project_create")
     * @Method("POST")
     * @Template("SGLFLTSBundle:Project:Crud/new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Project();
        $form = $this->createForm(new ProjectType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Created!'
            );

            return $this->redirect($this->generateUrl('sgl_flts_project_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Project entity.
     *
     * @Route("/{id}/edit", name="sgl_flts_project_edit")
     * @Template("SGLFLTSBundle:Project:Crud/edit.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SGLFLTSBundle:Project')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }

        $editForm = $this->createForm(new ProjectType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Project entity.
     *
     * @Route("/{id}/update", name="sgl_flts_project_update")
     * @Method("POST")
     * @Template("SGLFLTSBundle:Project:Crud/edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SGLFLTSBundle:Project')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ProjectType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Updated!'
            );

            return $this->redirect($this->generateUrl('sgl_flts_project_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Project entity.
     *
     * @Route("/{id}/delete", name="sgl_flts_project_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SGLFLTSBundle:Project')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Project entity.');
            }

            if ($entity->getParts()->count() > 0)
                return \Symfony\Component\HttpFoundation\Response::create('Can\'t delete Project with associated Parts.<br /><a href="#" onclick="history.go(-1);">Back</a>');

            $em->remove($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Deleted!'
            );
        }

        return $this->redirect($this->generateUrl('sgl_flts_project'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
