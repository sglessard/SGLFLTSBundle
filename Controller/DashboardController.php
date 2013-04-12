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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Dashboard controller.
 *
 * @Route("/")
 */
class DashboardController extends Controller
{
    /**
     * @Route("/", name="sgl_flts_dashboard")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $lastest_work = $em->getRepository('SGLFLTSBundle:Work')->retrieveLatest();
        $latest_bills = $em->getRepository('SGLFLTSBundle:Bill')->retrieveFixedLatest(5);

        return array(
            'lastest_work'=>$lastest_work,
            'latest_bills'=>$latest_bills
        );
    }
}
