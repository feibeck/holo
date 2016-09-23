<?php
/**
 * HOLO
 *
 * @copyright Copyright (c) 2016 Florian Eibeck
 * @license   THE BEER-WARE LICENSE (Revision 42)
 *
 * "THE BEER-WARE LICENSE" (Revision 42):
 * Florian Eibeck wrote this software. As long as you retain this notice you
 * can do whatever you want with this stuff. If we meet some day, and you think
 * this stuff is worth it, you can buy me a beer in return.
 */

namespace Holo;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Definition of the HOLO configuration
 */
class Configuration implements ConfigurationInterface
{

    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('app config');

        $rootNode->children()
            ->append($this->addMiddlewareSetup())
            ->append($this->addInverterSetup())
            ->append($this->addVentilationSetup())
            ->end();

        return $treeBuilder;
    }

    /**
     * @return ArrayNodeDefinition|NodeDefinition
     */
    public function addMiddlewareSetup()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('middleware');

        $node
            ->children()
                ->scalarNode('url')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
            ->end()
        ;

        return $node;
    }

    /**
     * @return ArrayNodeDefinition|NodeDefinition
     */
    public function addInverterSetup()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('inverter');

        $node
            ->children()
                ->scalarNode('hostname')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('username')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('password')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('uuid_total')->end()
                ->scalarNode('uuid_current')->end()
            ->end()
        ;

        return $node;
    }

    /**
     * @return ArrayNodeDefinition|NodeDefinition
     */
    public function addVentilationSetup()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('ventilation');

        $node
            ->children()
                ->scalarNode('hostname')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('password')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('uuid_air_outside_incoming')->end()
                ->scalarNode('uuid_air_outside_outgoing')->end()
                ->scalarNode('uuid_air_inside_incoming')->end()
                ->scalarNode('uuid_air_inside_outgoing')->end()
                ->scalarNode('uuid_setting_level')->end()
                ->scalarNode('uuid_setting_percent')->end()
                ->scalarNode('uuid_revolution_speed_incoming')->end()
                ->scalarNode('uuid_revolution_speed_outgoing')->end()
                ->scalarNode('uuid_state_bypass')->end()
            ->end()
        ;

        return $node;
    }

}
