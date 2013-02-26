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
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SGL\FLTSBundle\Entity\Bill;
use SGL\FLTSBundle\Form\BillType;
use SGL\FLTSBundle\Form\BillSentType;

/**
 * Bill controller.
 *
 * @Route("/")
 */
class BillController extends Controller
{
    /**
     * Lists latest Bill entities.
     *
     * @Route("/", name="sgl_flts_bill")
     * @Template("SGLFLTSBundle:Bill:List/index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $latest_period = $this->getRequest()->get('period',$this->container->getParameter('sgl_flts.bill_latest_period'));

        // Latest for sgl_flts.bill_latest_period param
        $now = new \DateTime();
        $limit_date = clone $now;
        $interval = new \DateInterval($latest_period);
        $limit_date->sub($interval);

        // Show more / link
        // We simply add the period to the last used period
        $limit_date_more = clone $limit_date;
        $limit_date_more->sub(new \DateInterval($this->container->getParameter('sgl_flts.bill_latest_period')));
        $interval_more = $now->diff($limit_date_more);

        $entities = $em->getRepository('SGLFLTSBundle:Bill')->retrieveLatest($limit_date);

        return array(
            'more_period'=>$interval_more->format('P%yY%mM'),
            'entities' => $entities,
        );
    }

    /**
    * Lists Bill entities by part
    *
    * @Route("/part/{id_part}", name="sgl_flts_part_bills")
    * @Template("SGLFLTSBundle:Bill:List/part_index.html.twig")
    */
   public function partAction($id_part)
   {
       $em = $this->getDoctrine()->getManager();

       $part = $em->getRepository('SGLFLTSBundle:Part')->find($id_part);
       if (!$part) {
           throw $this->createNotFoundException('Unable to find Part entity.');
       }

       $entities = $em->getRepository('SGLFLTSBundle:Bill')->retrieveByPartWithProject($id_part);

       return array(
           'entities' => $entities,
           'part'     => $part,
       );
   }

    /**
     * Finds and displays a Bill entity.
     *
     * @Route("/{id}/show", name="sgl_flts_bill_show")
     * @Template("SGLFLTSBundle:Bill:Crud/show.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SGLFLTSBundle:Bill')->findWithPartProjectWorks($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Bill entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'project'     => $entity->getPart()->getProject(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Bill entity.
     *
     * @Route("/new/{id_part}", name="sgl_flts_bill_new")
     * @Template("SGLFLTSBundle:Bill:Crud/new.html.twig")
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');
        $tax_calc = $this->get('tax');

        $entity = new Bill();
        $entity->setNumber($em->getRepository('SGLFLTSBundle:Bill')->findNextNumber());
        $entity->setName($translator->trans('Bill 1'));
        $entity->setGst($tax_calc->getGst());
        $entity->setPst($tax_calc->getPst());
        $entity->setHst($tax_calc->getHst());
        $entity->setBilledAt(new \DateTime);
        $entity->setTaxable($this->container->getParameter('sgl_flts.bill_taxable'));

        if ($request->get('id_part',0)) {
            $part = $em->getRepository('SGLFLTSBundle:Part')->find($request->get('id_part'));
            if ($part) {
                $entity->setPart($part);
                $entity->setRate($part->getProject()->getClient()->getRate());
            }
        }

        $form   = $this->createForm(new BillType(), $entity,array('new_entity'=>true));

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Bill entity.
     *
     * @Route("/create", name="sgl_flts_bill_create")
     * @Method("POST")
     * @Template("SGLFLTSBundle:Bill:Crud/new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Bill();
        $form = $this->createForm(new BillType(), $entity,array('new_entity'=>true));
        $form->bind($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            // Generate body content if empty
            if (!$entity->getBodyContent()) {
                $entity->setBodyContent($this->generateInvoiceBodyContent($entity));
            }

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('sgl_flts_bill_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Bill entity.
     *
     * @Route("/{id}/edit", name="sgl_flts_bill_edit")
     * @Template("SGLFLTSBundle:Bill:Crud/edit.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SGLFLTSBundle:Bill')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Bill entity.');
        }

        if (!$entity->getBodyContent()) {
            $entity->setBodyContent($this->generateInvoiceBodyContent($entity));
        }

        if ($entity->getSent()) {
            $editForm = $this->createForm(new BillSentType(), $entity);
        } else {
            $editForm = $this->createForm(new BillType(), $entity);
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'project'     => $entity->getPart()->getProject(),
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Bill entity.
     *
     * @Route("/{id}/update", name="sgl_flts_bill_update")
     * @Method("POST")
     * @Template("SGLFLTSBundle:Bill:Crud/edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SGLFLTSBundle:Bill')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Bill entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        if ($entity->getSent()) {
            $editForm = $this->createForm(new BillSentType(), $entity);
        } else {
            $editForm = $this->createForm(new BillType(), $entity);
        }

        $editForm->bind($request);

        if ($editForm->isValid()) {

            // Generate body content if empty
            if (!$entity->getBodyContent()) {
                $entity->setBodyContent($this->generateInvoiceBodyContent($entity));
            }

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('sgl_flts_bill_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Bill entity.
     *
     * @Route("/{id}/delete", name="sgl_flts_bill_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SGLFLTSBundle:Bill')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Bill entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sgl_flts_bill'));
    }


    /**
    * @Route("/{id}/work", name="sgl_flts_bill_works")
    * @Template("SGLFLTSBundle:Bill:List/work_index.html.twig")
    */
   public function editWorkAction($id)
   {
       $em = $this->getDoctrine()->getManager();
       $bill = $em->getRepository('SGLFLTSBundle:Bill')->find($id);

       if (!$bill) {
           throw $this->createNotFoundException('Unable to find Bill entity.');
       }

       $part = $bill->getPart();

       $bills_form = $this->createBillsForm($part);
       $bills_form->get('bill')->setData($bill);

       if ($bill->getSentAt() || $bill->getPaidAt()) {
           $tasks = $em->getRepository('SGLFLTSBundle:Task')->retrieveByBillWithWorks($bill->getId());
       } else {
           $tasks = $em->getRepository('SGLFLTSBundle:Task')->retrieveByPartAvailableWorksToBill($part->getId(), $bill->getId());
       }

       $deleteForm = $this->createDeleteForm($id);

       return array(
           'part'       => $part,
           'tasks'      => $tasks,
           'project'    => $part->getProject(),
           'bill'       => $bill,
           'bills_form' => $bills_form->createView(),
           'delete_form'=> $deleteForm->createView(),
       );
   }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**
    * Part's bill selection form
    * @param Part $part
    */
    private function createBillsForm($part)
    {
        return $this->createFormBuilder(null,array('csrf_protection' => false))
            ->add('bill','entity',array(
                'class'         => 'SGLFLTSBundle:Bill',
                'property'      => 'fullname',
                'query_builder' => function (\SGL\FLTSBundle\Entity\BillRepository $er) use ($part) {
                    return $er->retrieveByPartWithProject($part->getId(),true);
                }))
            ->getForm()
        ;
    }

    /**
     * @param \SGL\FLTSBundle\Entity\Bill $bill
     * @return Response
     */
    private function generateInvoiceBodyContent(Bill $bill) {
        return $this->render('SGLFLTSBundle:Bill:Invoice/body_content.html.twig', array('bill' => $bill, 'part'=>$bill->getPart()))->getContent();
    }

    /**
     * @param \SGL\FLTSBundle\Entity\Bill $bill
     * @return Response
     */
    private function generateInvoiceHTML(Bill $bill) {
        return $this->render(
            'SGLFLTSBundle:Bill:Invoice/content.html.twig',
            array(
                'bill'    => $bill,
                'part'    => $bill->getPart(),
                'project' => $bill->getPart()->getProject(),
                'client'  => $bill->getPart()->getProject()->getClient())
        )->getContent();
    }
}
