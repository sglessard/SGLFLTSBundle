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

/**
 * Invoice controller.
 *
 * @Route("/")
 */
class StatsController extends Controller
{
    /**
     * Show stats (HTML)
     *
     * @Template("SGLFLTSBundle:Stats:show.html.twig")
     */
    public function showAction()
    {
        $now = date('Y-m-d');
        $currentMonday = date('Y-m-d', strtotime('this week monday'));
        
        $lastWeekMonday = date('Y-m-d', strtotime('last week monday'));
        $lastWeekSunday = date('Y-m-d', strtotime('last week sunday'));
        
        $currentMonthFirstDay = date('Y-m-d', strtotime('first day of this month'));
        $currentMonthLastDay = date('Y-m-d', strtotime('last day of this month'));
        
        $lastMonthFirstDay = date('Y-m-d', strtotime('first day of last month'));
        $lastMonthLastDay = date('Y-m-d', strtotime('last day of last month'));
        
        $twelveMonthsAgo = date('Y-m-d', strtotime('-12 months', strtotime('first day of this month')));

        return array(
            'this_week_from' => $currentMonday,
            'this_week_to'   => $now,
            'last_week_from' => $lastWeekMonday,
            'last_week_to'   => $lastWeekSunday,
            'this_month_from' => $currentMonthFirstDay,
            'this_month_to' => $currentMonthLastDay,
            'last_month_from' => $lastMonthFirstDay,
            'last_month_to' => $lastMonthLastDay,
            'graph_from' => $twelveMonthsAgo,
        );
    }

    /**
     * Show week stats
     * 
     * @param $from
     * @param $to
     * @return array
     * 
     * @Template("SGLFLTSBundle:Stats:week.html.twig")
     */
    public function weekAction($from, $to)
    {
        $em = $this->getDoctrine()->getManager();
        
        $works = $em->getRepository('SGLFLTSBundle:Work')->findByDate($from, $to);
        
        $hours = 0;
        $money = 0;
        
        foreach ($works as $work) {
            $hours += $work->getDuration();
            $money += $work->getHours() * $work->getRate()->getRate();
        }
        
        // Add Invoice extra billed time/fees
        
        $bills = $em->getRepository('SGLFLTSBundle:Bill')->findByDate($from, $to);
        
        foreach ($bills as $bill) {
            $hours += $bill->getExtraHours();
            $money += $bill->getExtraHours() * $work->getRate()->getRate();
            $money += $bill->getExtraFees();
        }
        
        
        return array(
            'hours'           => $hours,
            'money'           => $money,
        );
    }

    /**
     * Show month stats
     * 
     * @param $from
     * @param $to
     *
     * @return array
     * 
     * @Template("SGLFLTSBundle:Stats:week.html.twig")
     */
    public function monthAction($from, $to)
    {
        $em = $this->getDoctrine()->getManager();
        
        $works = $em->getRepository('SGLFLTSBundle:Work')->findByDate($from, $to);
        
        $hours = 0;
        $money = 0;
        
        foreach ($works as $work) {
            $hours += $work->getDuration();
            $money += ($work->getHours() * $work->getRate()->getRate());
        }
        
        // Add Invoice extra billed time/fees
        
        $bills = $em->getRepository('SGLFLTSBundle:Bill')->findByDate($from, $to);
        
        foreach ($bills as $bill) {
            $hours += $bill->getExtraHours();
            $money += $bill->getExtraHours() * $work->getRate()->getRate();
            $money += $bill->getExtraFees();
        }

        return array(
            'hours'           => $hours,
            'money'           => $money,
        );
    }

    /**
     * Show graph (svg)
     * 
     * @param $from
     * @param $to
     *
     * @return array
     * 
     * @Template("SGLFLTSBundle:Stats:graph.html.twig")
     */
    public function graphAction($from, $to)
    {
        $em = $this->getDoctrine()->getManager();
        
        $works = $em->getRepository('SGLFLTSBundle:Work')->findByDate($from, $to);
        
        $hours = 0;
        $money = 0;
        
        foreach ($works as $work) {
            $hours += $work->getDuration();
            $money += ($work->getHours() * $work->getRate()->getRate());
        }
        
        // Add Invoice extra billed time/fees
        
        $bills = $em->getRepository('SGLFLTSBundle:Bill')->findByDate($from, $to);
        
        foreach ($bills as $bill) {
            $hours += $bill->getExtraHours();
            $money += $bill->getExtraHours() * $work->getRate()->getRate();
            $money += $bill->getExtraFees();
        }

        return array(
            'hours'           => $hours,
            'money'           => $money,
            'graph_from' => $from,
            'graph_to' => $to,
        );
    }
}
