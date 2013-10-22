<?php

/*
 * This file is part of the SGLFLTSBundle package.
 *
 * (c) Simon Guillem-Lessard <s.g.lessard@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SGL\FLTSBundle\Listeners;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class RequestListener
{
    protected $container;
    protected $recent_parts_limit;

    public function __construct(ContainerInterface $container, $recent_parts_limit)
    {
        $this->container = $container;
        $this->recent_parts_limit = $recent_parts_limit;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernel::MASTER_REQUEST != $event->getRequestType())
            return;

        $kernel    = $event->getKernel();
        $request   = $event->getRequest();

        // Save recent part.id
        $this->saveRecentParts($request, $kernel);
    }

    /**
     * Set recent part.id in session
     *
     * @param Request $request
     * @param HttpKernel $kernel
     */
    public function saveRecentParts(Request $request, HttpKernel $kernel)
    {
        $session = $this->container->get('session');
        $parts = array();

        if ($session->has('recentparts')) {
            $parts = explode(',',$session->get('recentparts',','));
        }

        // Cases to get part.id from route parameters
        if ($request->get('id_part')) {
            $part_id = $request->get('id_part');
        } else if (preg_match('/^sgl_flts_part_/',$request->get('_route'))) {
            $part_id = $request->get('id');
        } else {
            $part_id = null;
        }

        if (intval($part_id) > 0) {
            if (!in_array($part_id,$parts)) {

                // If full, pop the oldest part
                if (sizeof($parts) >= $this->recent_parts_limit) {
                    array_pop($parts);
                }

                // Most recent in first place
                array_unshift($parts, $part_id);

                // Save in session
                $session->set('recentparts',implode(',',$parts));
            }
        }
    }
}
