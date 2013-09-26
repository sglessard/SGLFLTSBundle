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
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SGL\FLTSBundle\Entity\Part;
use SGL\FLTSBundle\Form\PartType;
use SGL\FLTSBundle\Entity\Task;

/**
 * Part controller.
 *
 * @Route("/")
 */
class PartController extends Controller
{
    /**
     * Lists all Project entities.
     *
     * @Route("/", name="sgl_flts_part")
     * @Template("SGLFLTSBundle:Part:List/index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $clients = $em->getRepository('SGLFLTSBundle:Client')->retrieveWithOpenedProjects();

        // Session
        $this->getRequest()->getSession()->set('opened_parts', true);

        return array(
            'clients' => $clients,
        );
    }

    /**
     * Lists opened Project Part entities.
     *
     * @Route("/{id_project}/list", name="sgl_flts_part_list")
     * @Template("SGLFLTSBundle:Part:List/list.html.twig")
     */
    public function listAction($id_project, $opened_parts=true)
    {
        $em = $this->getDoctrine()->getManager();

        $project = $em->getRepository('SGLFLTSBundle:Project')->find($id_project);

        if (!$project) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }

        if ($opened_parts) {
            $parts = $em->getRepository('SGLFLTSBundle:Part')->retrieveOpenedByProjectWithTaskWorkBill($id_project);
        } else {
            $parts = $em->getRepository('SGLFLTSBundle:Part')->retrieveByProjectWithTaskWorkBill($id_project);
        }

        // Session
        $this->getRequest()->getSession()->set('opened_parts', $opened_parts);

        return array(
            'entities' => $parts,
            'project' => $project,
        );
    }

    /**
     * Finds and displays a Part entity.
     *
     * @Route("/{id_project}/{id}/show", name="sgl_flts_part_show")
     * @Template("SGLFLTSBundle:Part:Crud/show.html.twig")
     */
    public function showAction($id_project,$id, $opened_parts=true)
    {
        $em = $this->getDoctrine()->getManager();

        $project = $em->getRepository('SGLFLTSBundle:Project')->find($id_project);

        if (!$project) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }

        $entity = $em->getRepository('SGLFLTSBundle:Part')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Part entity.');
        }

        $deleteForm = $this->createDeleteForm($id, $entity->getClosed());

        // Session
        $this->getRequest()->getSession()->set('opened_parts', $opened_parts);

        return array(
            'part'      => $entity,
            'project'     => $project,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Part entity.
     *
     * @Route("/{id_project}/new", name="sgl_flts_part_new")
     * @Template("SGLFLTSBundle:Part:Crud/new.html.twig")
     */
    public function newAction($id_project, $opened_parts=true)
    {
        $em = $this->getDoctrine()->getManager();

        $project = $em->getRepository('SGLFLTSBundle:Project')->find($id_project);

        if (!$project) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }

        $entity = new Part();
        $entity->setProject($project);
        $entity->setStartedAt(new \DateTime);

        $form   = $this->createForm(new PartType(), $entity, array('client'=>$project->getClient()));

        return array(
            'part' => $entity,
            'project'=> $project,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Part entity.
     *
     * @Route("/create", name="sgl_flts_part_create")
     * @Method("POST")
     * @Template("SGLFLTSBundle:Part:Crud/new.html.twig")
     */
    public function createAction(Request $request, $opened_parts=true)
    {
        $em = $this->getDoctrine()->getManager();
        $part_type = new PartType();

        $post_val = $request->get($part_type->getName());

        $project = $em->getRepository('SGLFLTSBundle:Project')->find($post_val['project']);
        unset($post_val);

        if (!$project) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }

        $entity  = new Part();
        $form = $this->createForm($part_type, $entity, array('client'=>$project->getClient()));
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

           $project = $entity->getProject();

            // Create frequent tasks
            $this->createFrequentTasks($entity);

            $redirect = $opened_parts ? 'sgl_flts_part_show':'sgl_flts_part_show_all';
            return $this->redirect($this->generateUrl($redirect, array('id' => $entity->getId(), 'id_project'=>$project->getId())));
        }

        return array(
            'part' => $entity,
            'project'=> $project,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Part entity.
     *
     * @Route("/{id_project}/{id}/edit", name="sgl_flts_part_edit")
     * @Template("SGLFLTSBundle:Part:Crud/edit.html.twig")
     */
    public function editAction($id_project, $id, $opened_parts=true)
    {
        $em = $this->getDoctrine()->getManager();

        $project = $em->getRepository('SGLFLTSBundle:Project')->find($id_project);

        if (!$project) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }

        $entity = $em->getRepository('SGLFLTSBundle:Part')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Part entity.');
        }

        $editForm = $this->createForm(new PartType(), $entity,array('client'=>$project->getClient()));
        $deleteForm = $this->createDeleteForm($id, $entity->getClosed());

        return array(
            'part'      => $entity,
            'project'     => $project,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Part entity.
     *
     * @Route("/{id}/update", name="sgl_flts_part_update")
     * @Method("POST")
     * @Template("SGLFLTSBundle:Part:Crud/edit.html.twig")
     */
    public function updateAction(Request $request, $id, $opened_parts=true)
    {
        $em = $this->getDoctrine()->getManager();
        $part_type = new PartType();

        $entity = $em->getRepository('SGLFLTSBundle:Part')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Part entity.');
        }

        $post_val = $request->get($part_type->getName());

        $project = $em->getRepository('SGLFLTSBundle:Project')->find($post_val['project']);
        unset($post_val);

        if (!$project) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }

        $deleteForm = $this->createDeleteForm($id, $entity->getClosed());
        $editForm = $this->createForm($part_type, $entity, array('client'=>$project->getClient()));
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            $project = $entity->getProject();

            $redirect = $opened_parts ? 'sgl_flts_part_edit':'sgl_flts_part_edit_all';
            return $this->redirect($this->generateUrl($redirect, array('id' => $id, 'id_project'=>$project->getId())));
        }

        return array(
            'entity'      => $entity,
            'project'     => $project,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Part entity.
     *
     * @Route("/{id}/delete", name="sgl_flts_part_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SGLFLTSBundle:Part')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Part entity.');
        }

        // Only closed parts can be deleted
        if (!$entity->getClosed()) {
            throw new HttpException(403,'Forbidden : Close the project part before deleting it.');
        }

        $project = $entity->getProject();

        $form = $this->createDeleteForm($id, $entity->getClosed());
        $form->bind($request);

        if ($form->isValid()) {
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sgl_flts_part_list_all',array('id_project'=>$project->getId())));
    }

    /**
     * All Projects view
     * Lists ALL Project Part entities
     *
     * @Route("/{id_project}/list/all", name="sgl_flts_part_list_all")
     * @Template("SGLFLTSBundle:Part:List/list.html.twig")
     */
    public function listAllAction($id_project)
    {
        return $this->listAction($id_project, false);
    }

    /**
     * All Projects view
     * Displays a form to create a new Part entity.
     *
     * @Route("/{id_project}/new/all", name="sgl_flts_part_new_all")
     * @Template("SGLFLTSBundle:Part:Crud/new.html.twig")
     */
    public function newAllAction($id_project) {
        return $this->newAction($id_project, false);
    }

    /**
     * All Projects view
     * Creates a new Part entity.
     *
     * @Route("/create/all", name="sgl_flts_part_create_all")
     * @Method("POST")
     * @Template("SGLFLTSBundle:Part:Crud/new.html.twig")
     */
    public function createAllAction(Request $request) {
        return $this->createAction($request, false);
    }

    /**
     * Finds and displays a Part entity
     * All Projects view
     *
     * @Route("/{id_project}/{id}/show/all", name="sgl_flts_part_show_all")
     * @Template("SGLFLTSBundle:Part:Crud/show.html.twig")
     */
    public function showAllAction($id_project,$id)
    {
        return $this->showAction($id_project,$id, false);
    }
    /**
     * All Projects view
     * Displays a form to edit an existing Part entity
     *
     * @Route("/{id_project}/{id}/edit/all", name="sgl_flts_part_edit_all")
     * @Template("SGLFLTSBundle:Part:Crud/edit.html.twig")
     */
    public function editAllAction($id_project,$id)
    {
        return $this->editAction($id_project,$id, false);
    }

    /**
     * All Projects view
     * Edits an existing Part entity.
     *
     * @Route("/{id}/update/all", name="sgl_flts_part_update_all")
     * @Method("POST")
     * @Template("SGLFLTSBundle:Part:Crud/edit.html.twig")
     */
    public function updateAllAction(Request $request, $id)
    {
        return $this->updateAction($request,$id, false);
    }

    /**
     * Part selection form component
     * It also handles the post action
     *
     * @Route("/selection/{opened_only}", name="sgl_flts_part_selection")
     * @Method("POST")
     *
     * @param string $redirect_route
     * @param string $redirect_error
     * @param Part $part    false, null or Part object
     * @param Part $opened_part    false, null or Part object
     * @param string $opened_only '0' or '1' (from render or from post action form)
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Template("SGLFLTSBundle:Part:Form/selection.html.twig")
     */
    public function selectionAction(Request $request, $opened_part=null, $part=null, $redirect_route=null, $redirect_error=null, $opened_only='0')
    {
        $form = $this->createSelectPartForm($opened_only == '1');

        if ($request->getMethod() == 'POST') {

            $form->bind($request);
            $data = $form->getData();

            if ($form->isValid()) {
                $part_id = isset($data['opened_part']) ? $data['opened_part']->getId() : $data['part']->getId();
                return $this->redirect($this->generateUrl($data['redirect_route'],array('id_part'=>$part_id)));
            } else {
                // Error, back to previous action
                $post_var = $request->request->get('form');
                return $this->redirect($this->generateUrl($post_var['redirect_error']).'?error=true');
            }
        }

        if ($redirect_route) {
            $form->get('redirect_route')->setData($redirect_route);
        }
        if ($redirect_error) {
            $form->get('redirect_error')->setData($redirect_error);
        }
        if ($part) {
            $form->get('part')->setData($part);
        }

        return array(
            'form'=>$form->createView(),
            'form_attr_id'=>$opened_only == '1' ? 'opened_part_select_form' : 'part_select_form'
        );
    }

    /**
     * Part deletion form
     * @param integer $id
     * @param boolean $closed
     *
     * @return FormBuilder
     */
    private function createDeleteForm($id,$closed)
    {
        return $this->createFormBuilder(array('id' => $id, 'closed'=>$closed))
            ->add('id', 'hidden')
            ->add('closed', 'hidden')
            ->getForm()
        ;
    }

    /**
     * Part selection form
     * @param boolean $opened
     *
     * @return FormBuilder
     */
    private function createSelectPartForm($opened_parts=true)
    {
        $part_field_id = $opened_parts ? 'opened_part' : 'part';
        return $this->createFormBuilder(null,array('csrf_protection' => false))
            ->add($part_field_id,'entity',array(
                'class'         => 'SGLFLTSBundle:Part',
                'property'      => 'fullname',
                'group_by'      => 'clientName',
                'query_builder' => function (\SGL\FLTSBundle\Entity\PartRepository $er) use ($opened_parts) {
                    if ($opened_parts) {
                        return $er->retrieveOpened(true);
                    } else {
                        return $er->retrieveWithProjectClient(true);
                    }
                }))
            ->add('redirect_route', 'hidden')
            ->add('redirect_error', 'hidden')
            ->getForm()
        ;
    }

    /**
     * Create frequent tasks for a project part
     *
     * @param \SGL\FLTSBundle\Entity\Part $part
     * @return void
     */
    private function createFrequentTasks(\SGL\FLTSBundle\Entity\Part $part)
    {
        $em = $this->getDoctrine()->getManager();

        foreach ($em->getRepository('SGLFLTSBundle:FrequentTask')->findAll() as $frequenttask) {
            $task = new Task();
            $task->setName($frequenttask->getName());
            $task->setIdentification($frequenttask->getIdentification());
            $task->setPart($part);
            $task->setRank($frequenttask->getRank());

            $em->persist($task);
            $em->flush();
            unset($task);
        }
    }
}
