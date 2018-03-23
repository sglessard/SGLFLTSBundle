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
use SGL\FLTSBundle\Entity\User;
use SGL\FLTSBundle\Form\UserType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

/**
 * User controller.
 *
 * @Route("/")
 */
class UserController extends Controller
{
    /**
     * Lists all User entities.
     *
     * @return array
     * 
     * @Route("/", name="sgl_flts_user")
     * @Template("SGLFLTSBundle:User:List/index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SGLFLTSBundle:User')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a User entity.
     *
     * @param int $id
     * @return array
     * 
     * @Route("/{id}/show", name="sgl_flts_user_show")
     * @Template("SGLFLTSBundle:User:Crud/show.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SGLFLTSBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new User entity.
     *
     * @return array
     * 
     * @Route("/new", name="sgl_flts_user_new")
     * @Template("SGLFLTSBundle:User:Crud/new.html.twig")
     */
    public function newAction()
    {
        $entity = new User();

        $formFactory = $this->get('fos_user.registration.form.factory');

        $form = $formFactory->createForm([
            'action' => $this->generateUrl('sgl_flts_user_create')
        ]);
        $form->setData($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new User entity.
     *
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * 
     * @Route("/create", name="sgl_flts_user_create")
     * @Method("POST")
     * @Template("SGLFLTSBundle:User:Crud/new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new User();

        $formFactory = $this->get('fos_user.registration.form.factory');

        $form = $formFactory->createForm([
            'action' => $this->generateUrl('sgl_flts_user_create')
        ]);
        $form->setData($entity);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Created!'
            );

            return $this->redirect($this->generateUrl('sgl_flts_user_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @param int $id
     * @return array
     * 
     * @Route("/{id}/edit", name="sgl_flts_user_edit")
     * @Template("SGLFLTSBundle:User:Crud/edit.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SGLFLTSBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $formFactory = $this->get('fos_user.profile.form.factory');

        $editForm = $formFactory->createForm([
            'action' => $this->generateUrl('sgl_flts_user_update', ['id' => $id]),
            'validation_groups'=>'Profile'
        ]);
        $editForm->setData($entity);

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing User entity.
     *
     * @param Request $request
     * @param int $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * 
     * @Route("/{id}/update", name="sgl_flts_user_update")
     * @Method("POST")
     * @Template("SGLFLTSBundle:User:Crud/edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SGLFLTSBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        $formFactory = $this->get('fos_user.profile.form.factory');

        $editForm = $formFactory->createForm([
            'action' => $this->generateUrl('sgl_flts_user_update', ['id' => $id]),
            'validation_groups'=>'Profile'
        ]);
        $editForm->setData($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Updated!'
            );

            return $this->redirect($this->generateUrl('sgl_flts_user_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a User entity.
     *
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * 
     * @Route("/{id}/delete", name="sgl_flts_user_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SGLFLTSBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Deleted!'
            );
        }

        return $this->redirect($this->generateUrl('sgl_flts_user'));
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
