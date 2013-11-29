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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sgl_flts');

        $rootNode
            ->children()
            ->scalarNode('bill_latest_period')->defaultValue('P3M')->end()
            ->booleanNode('bill_taxable')->defaultValue(true)->end()
            ->scalarNode('bill_hst_registration_number')->defaultValue('0')->end()
            ->scalarNode('bill_pst_registration_number')->defaultValue('TQ0001')->end()
            ->scalarNode('bill_gst_registration_number')->defaultValue('RT0001')->end()
            ->scalarNode('business_name')->defaultValue('Independent Symfony developers')->end()
            ->scalarNode('business_logo_src')->defaultValue('/bundles/sglflts/images/logos/default.png')->end()
            ->scalarNode('business_logo_width')->defaultValue('300')->end()
            ->scalarNode('business_invoice_logo_src')->defaultValue('/bundles/sglflts/images/logos/default.png')->end()
            ->scalarNode('business_invoice_logo_width')->defaultValue('300')->end()
            ->scalarNode('business_address')->defaultValue('1000, my street\n mycity, my province\n my country my postal code')->end()
            ->scalarNode('business_phone')->defaultValue('010 110-0110')->end()

            ->end();

        return $treeBuilder;
    }
}
