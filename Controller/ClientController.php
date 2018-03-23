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
use SGL\FLTSBundle\Entity\Client;
use SGL\FLTSBundle\Form\ClientType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

/**
 * Client controller.
 *
 * @Route("/")
 */
class ClientController extends Controller
{
    /**
     * Lists all Client entities.
     *
     * @Route("/", name="sgl_flts_client")
     * @Template("SGLFLTSBundle:Client:List/index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SGLFLTSBundle:Client')->retrieve();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Client entity.
     *
     * @Route("/{id}/show", name="sgl_flts_client_show")
     * @Template("SGLFLTSBundle:Client:Crud/show.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SGLFLTSBundle:Client')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Client entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Client entity.
     *
     * @Route("/new", name="sgl_flts_client_new")
     * @Template("SGLFLTSBundle:Client:Crud/new.html.twig")
     */
    public function newAction()
    {
        $entity = new Client();
        $form   = $this->createForm(ClientType::class, $entity, [
            'action' => $this->generateUrl('sgl_flts_client_create')
        ]);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Client entity.
     *
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * 
     * @Route("/create", name="sgl_flts_client_create")
     * @Method("POST")
     * @Template("SGLFLTSBundle:Client:Crud/new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Client();
        $form = $this->createForm(ClientType::class, $entity, [
            'action' => $this->generateUrl('sgl_flts_client_create')
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

            return $this->redirect($this->generateUrl('sgl_flts_client_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Client entity.
     *
     * @param int $id
     * @return array
     * 
     * @Route("/{id}/edit", name="sgl_flts_client_edit")
     * @Template("SGLFLTSBundle:Client:Crud/edit.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SGLFLTSBundle:Client')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Client entity.');
        }

        $editForm = $this->createForm(ClientType::class, $entity, [
            'action' => $this->generateUrl('sgl_flts_client_update', ['id' => $id])
        ]);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Client entity.
     *
     * @param Request $request
     * @param int $id
     * @return array
     * 
     * @Route("/{id}/update", name="sgl_flts_client_update")
     * @Method("POST")
     * @Template("SGLFLTSBundle:Client:Crud/edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SGLFLTSBundle:Client')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Client entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(ClientType::class, $entity, [
            'action' => $this->generateUrl('sgl_flts_client_update', ['id' => $id])
        ]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            // Fires PreUpdate/PostUpdate even if no field has changed
            $files = $request->files->get($editForm->getName());
            if ($files['logo']) {
                $entity->removeUpload();
                $entity->setUpdatedAt(new \DateTime);
            }

            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Updated!'
            );

            return $this->redirect($this->generateUrl('sgl_flts_client_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Client entity.
     *
     * @Route("/{id}/delete", name="sgl_flts_client_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SGLFLTSBundle:Client')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Client entity.');
            }

            $em->remove($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Deleted!'
            );
        }

        return $this->redirect($this->generateUrl('sgl_flts_client'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', HiddenType::class)
            ->getForm()
        ;
    }
}
