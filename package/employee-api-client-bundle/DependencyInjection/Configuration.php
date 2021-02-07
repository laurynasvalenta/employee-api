<?php

namespace Package\EmployeeApiClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('employee_api_client');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('api_base_url')
                  ->isRequired()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
