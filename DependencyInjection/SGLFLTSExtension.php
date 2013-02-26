<?php

/*
 * This file is part of the SGLFLTSBundle package.
 *
 * (c) Simon Guillem-Lessard <s.g.lessard@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SGL\FLTSBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SGLFLTSExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('sgl_flts.bill_latest_period', $config['bill_latest_period']);
        $container->setParameter('sgl_flts.bill_taxable', $config['bill_taxable']);
        $container->setParameter('sgl_flts.bill_gst_registration_number', $config['bill_gst_registration_number']);
        $container->setParameter('sgl_flts.bill_pst_registration_number', $config['bill_pst_registration_number']);
        $container->setParameter('sgl_flts.bill_hst_registration_number', $config['bill_hst_registration_number']);

        $container->setParameter('sgl_flts.business_logo_src', $config['business_logo_src']);
        $container->setParameter('sgl_flts.business_logo_width', $config['business_logo_width']);
        $container->setParameter('sgl_flts.business_address', $config['business_address']);
        $container->setParameter('sgl_flts.business_phone', $config['business_phone']);

        $container->setParameter('sgl_flts.business_invoice_logo_src', $config['business_invoice_logo_src']);
        $container->setParameter('sgl_flts.business_invoice_logo_width', $config['business_invoice_logo_width']);

    }
}
