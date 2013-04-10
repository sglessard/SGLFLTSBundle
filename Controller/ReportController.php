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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use SGL\FLTSBundle\Entity\Part;
use SGL\FLTSBundle\Entity\Project;

/**
 * Report controller.
 */
class ReportController extends Controller
{
    /**
     * @Route("/", name="sgl_flts_report")
     * @Template()
     */
    public function indexAction()
    {
        return $this->redirect($this->generateUrl('sgl_flts_report_now'));
    }

    /**
    * @Route("/now", name="sgl_flts_report_now")
    * @Template("SGLFLTSBundle:Report:Date/index.html.twig")
    */
   public function nowAction()
   {
       $date = new \DateTime();
       return $this->redirect($this->generateUrl('sgl_flts_report_date',array('date'=>$date->format('Y-m-d'))));
   }

    /**
    * @Route("/date/search", name="sgl_flts_report_date_search")
    * @Template("SGLFLTSBundle:Report:Date/index.html.twig")
    * @Method("POST")
    */
   public function dateSearchAction(Request $request)
   {
       $search_form = $this->createSearchDateForm();
       $search_form->bind($request);

        if ($search_form->isValid()) {
            $data = $search_form->getData();
            return $this->redirect($this->generateUrl('sgl_flts_report_date',array('date'=>$data['date']->format('Y-m-d'))));
        } else {
            return $this->redirect($this->generateUrl('sgl_flts_report_now'));
        }
   }

    /**
    * @Route("/date/{date}", name="sgl_flts_report_date")
    * @Template("SGLFLTSBundle:Report:Date/index.html.twig")
    */
    public function dateAction($date)
    {
        $day = \DateTime::createFromFormat('Y-m-d', $date);
        $date_errors = \DateTime::getLastErrors();

        if ($date_errors['warning_count'] + $date_errors['error_count'] > 0) {
            throw $this->createNotFoundException('Input date does not exist.');
        }

        $next_day = clone $day;
        $next_day->add(new \DateInterval('P1D'));

        $previous_day = clone $day;
        $previous_day->sub(new \DateInterval('P1D'));

        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('SGLFLTSBundle:Work')->retrieveByDate($day);

        // Date search form
        $search_form = $this->createSearchDateForm();
        $search_form->get('date')->setData($day);

        return array(
            'next_day'=>$next_day,
            'previous_day'=>$previous_day,
            'entities'=>$entities,
            'form' => $search_form->createView(),
        );
    }

    /**
    * @Route("/part/search", name="sgl_flts_report_part_search")
    * @Template("SGLFLTSBundle:Report:Part/index.html.twig")
    */
   public function partSearchAction(Request $request)
   {
       return array(
           'part' => null,
       );
   }

    /**
    * @Route("/part/{id_part}", name="sgl_flts_report_part")
    * @Template("SGLFLTSBundle:Report:Part/index.html.twig")
    */
    public function partAction($id_part)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SGLFLTSBundle:Part')->find($id_part);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Part entity.');
        }

        $tasks = $em->getRepository('SGLFLTSBundle:Task')->retrieveByPartWithWorksToBill($id_part);

        return array(
            'part'=>$entity,
            'tasks'=>$tasks,
        );
    }

    /**
    * Date selection form
    */
    private function createSearchDateForm()
    {
        return $this->createFormBuilder(null,array('csrf_protection' => false))
            ->add('date', 'genemu_jquerydate',array(
                'required' => true,
                'widget' => 'single_text'
            ))
            ->getForm()
        ;
    }
}
