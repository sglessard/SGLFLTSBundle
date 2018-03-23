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
use SGL\FLTSBundle\Entity\Task;
use SGL\FLTSBundle\Form\TaskType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

/**
 * Task controller.
 *
 * @Route("/")
 */
class TaskController extends Controller
{
    /**
     * Lists all Task entities.
     *
     * @param int $id_project
     * @param int $id_part
     * @return array
     * 
     * @Route("/{id_project}/{id_part}/list", name="sgl_flts_task")
     * @Template("SGLFLTSBundle:Task:List/index.html.twig")
     */
    public function indexAction($id_project,$id_part)
    {
        $em = $this->getDoctrine()->getManager();

        $part = $em->getRepository('SGLFLTSBundle:Part')->find($id_part);

        if (!$part) {
            throw $this->createNotFoundException('Unable to find Part entity.');
        }

        $tasks = $em->getRepository('SGLFLTSBundle:Task')->retrieveWithWorksByPart($id_part);

        return array(
            'entities' => $tasks,
            'project'  => $part->getProject(),
            'part'     => $part,
        );
    }

    /**
     * Finds and displays a Task entity.
     *
     * @param int $id_project
     * @param int $id_part
     * @param int $id
     * @return array
     * 
     * @Route("/{id_project}/{id_part}/{id}/show", name="sgl_flts_task_show")
     * @Template("SGLFLTSBundle:Task:Crud/show.html.twig")
     */
    public function showAction($id_project,$id_part,$id)
    {
        $em = $this->getDoctrine()->getManager();

        $part = $em->getRepository('SGLFLTSBundle:Part')->find($id_part);

        if (!$part) {
            throw $this->createNotFoundException('Unable to find Part entity.');
        }

        $entity = $em->getRepository('SGLFLTSBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'project'     => $part->getProject(),
            'part'        => $part,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Task entity.
     *
     * @param int $id_project
     * @param int $id_part
     * @return array
     * 
     * @Route("/{id_project}/{id_part}/new", name="sgl_flts_task_new")
     * @Template("SGLFLTSBundle:Task:Crud/new.html.twig")
     */
    public function newAction($id_project,$id_part)
    {
        $em = $this->getDoctrine()->getManager();

        $part = $em->getRepository('SGLFLTSBundle:Part')->find($id_part);

        if (!$part) {
            throw $this->createNotFoundException('Unable to find Part entity.');
        }

        $entity = new Task();
        $entity->setPart($part);

        $form = $this->createForm(TaskType::class, $entity, [
            'action' => $this->generateUrl('sgl_flts_task_create'),
            'project'=>$part->getProject()
        ]);

        return array(
            'entity'   => $entity,
            'project'  => $part->getProject(),
            'part'     => $part,
            'form'     => $form->createView(),
        );
    }

    /**
     * Creates a new Task entity.
     *
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * 
     * @Route("/create", name="sgl_flts_task_create")
     * @Method("POST")
     * @Template("SGLFLTSBundle:Task:Crud/new.html.twig")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $task_type = new TaskType();

        $post_val = $request->get($task_type->getName());

        $part = $em->getRepository('SGLFLTSBundle:Part')->find($post_val['part']);
        unset($post_val);

        if (!$part) {
            throw $this->createNotFoundException('Unable to find Part entity.');
        }

        $entity  = new Task();
        $form = $this->createForm(TaskType::class, $entity, [
            'action' => $this->generateUrl('sgl_flts_task_create'),
            'project'=>$part->getProject()
        ]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();

            $part = $entity->getPart();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Created!'
            );

            return $this->redirect($this->generateUrl('sgl_flts_task_show', array('id' => $entity->getId(), 'id_project'=>$part->getProject()->getId(), 'id_part'=>$part->getId())));
        }

        return array(
            'entity'  => $entity,
            'project' => $part->getProject(),
            'part'    => $part,
            'form'    => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Task entity.
     *
     * @param int $id_project
     * @param int $id_part
     * @param int $id
     * @return array
     * 
     * @Route("/{id_project}/{id_part}/{id}/edit", name="sgl_flts_task_edit")
     * @Template("SGLFLTSBundle:Task:Crud/edit.html.twig")
     */
    public function editAction($id_project,$id_part,$id)
    {
        $em = $this->getDoctrine()->getManager();

        $part = $em->getRepository('SGLFLTSBundle:Part')->find($id_part);

        if (!$part) {
            throw $this->createNotFoundException('Unable to find Part entity.');
        }

        $entity = $em->getRepository('SGLFLTSBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $editForm = $this->createForm(TaskType::class, $entity, [
            'action' => $this->generateUrl('sgl_flts_task_update', ['id' => $id]),
            'project'=>$part->getProject()
        ]);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'project'  => $part->getProject(),
            'part'     => $part,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Task entity.
     *
     * @param Request $request
     * @param int $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * 
     * @Route("/{id}/update", name="sgl_flts_task_update")
     * @Method("POST")
     * @Template("SGLFLTSBundle:Task:Crud/edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $task_type = new TaskType();

        $post_val = $request->get($task_type->getName());

        $part = $em->getRepository('SGLFLTSBundle:Part')->find($post_val['part']);
        unset($post_val);

        if (!$part) {
            throw $this->createNotFoundException('Unable to find Part entity.');
        }

        $entity = $em->getRepository('SGLFLTSBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(TaskType::class, $entity, [
            'action' => $this->generateUrl('sgl_flts_task_update', ['id' => $id]),
            'project'=>$part->getProject()
        ]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            $part = $entity->getPart();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Updated!'
            );

            return $this->redirect($this->generateUrl('sgl_flts_task_edit', array('id' => $id, 'id_project'=>$part->getProject()->getId(), 'id_part'=>$part->getId())));
        }

        return array(
            'entity'      => $entity,
            'project'     => $part->getProject(),
            'part'        => $part,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Task entity.
     *
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * 
     * @Route("/{id}/delete", name="sgl_flts_task_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SGLFLTSBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $part = $entity->getPart();

        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->remove($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Deleted!'
            );
        }

        return $this->redirect($this->generateUrl('sgl_flts_task', array('id_project'=>$part->getProject()->getId(), 'id_part'=>$part->getId())));
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
