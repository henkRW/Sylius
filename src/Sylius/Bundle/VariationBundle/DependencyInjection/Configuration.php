<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\VariationBundle\DependencyInjection;

use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This class contains the configuration information for the bundle.
 *
 * This information is solely responsible for how the different configuration
 * sections are normalized, and merged.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sylius_variation');

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('driver')->defaultValue(SyliusResourceBundle::DRIVER_DOCTRINE_ORM)->end()
            ->end()
        ;

        $this->addClassesSection($rootNode);
        $this->addValidationGroupsSection($rootNode);

        return $treeBuilder;
    }

    /**
     * Adds `validation_groups` section.
     *
     * @param ArrayNodeDefinition $node
     */
    private function addValidationGroupsSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('validation_groups')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->arrayNode('variant')
                                ->prototype('scalar')->end()
                                ->defaultValue(array('sylius'))
                            ->end()
                            ->arrayNode('option')
                                ->prototype('scalar')->end()
                                ->defaultValue(array('sylius'))
                            ->end()
                            ->arrayNode('option_translation')
                                ->prototype('scalar')->end()
                                ->defaultValue(array('sylius'))
                            ->end()
                            ->arrayNode('option_value')
                                ->prototype('scalar')->end()
                                ->defaultValue(array('sylius'))
                            ->end()
                        ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * Adds `classes` section.
     *
     * @param ArrayNodeDefinition $node
     */
    private function addClassesSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('classes')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('variable')->isRequired()->end()
                            ->arrayNode('variant')
                                ->isRequired()
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('model')->isRequired()->end()
                                    ->scalarNode('controller')->defaultValue('Sylius\Bundle\ResourceBundle\Controller\ResourceController')->end()
                                    ->scalarNode('repository')->cannotBeEmpty()->end()
                                    ->scalarNode('form')->defaultValue('Sylius\Bundle\VariationBundle\Form\Type\VariantType')->end()
                                ->end()
                            ->end()
                            ->arrayNode('option')
                                ->isRequired()
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('model')->isRequired()->end()
                                    ->scalarNode('controller')->defaultValue('Sylius\Bundle\ResourceBundle\Controller\ResourceController')->end()
                                    ->scalarNode('repository')->cannotBeEmpty()->end()
                                    ->scalarNode('form')->defaultValue('Sylius\Bundle\VariationBundle\Form\Type\OptionType')->end()
                                    ->arrayNode('translation')
                                        ->addDefaultsIfNotSet()
                                        ->children()
                                            ->scalarNode('model')->defaultValue('Sylius\Component\Variation\Model\OptionTranslation')->end()
                                            ->scalarNode('controller')->defaultValue('Sylius\Bundle\ResourceBundle\Controller\ResourceController')->end()
                                            ->scalarNode('repository')->end()
                                            ->arrayNode('form')
                                                ->addDefaultsIfNotSet()
                                                ->children()
                                                    ->scalarNode('default')->defaultValue('Sylius\Bundle\VariationBundle\Form\Type\OptionTranslationType')->end()
                                                ->end()
                                            ->end()
                                            ->arrayNode('mapping')
                                                ->addDefaultsIfNotSet()
                                                ->children()
                                                    ->arrayNode('fields')
                                                        ->prototype('scalar')->end()
                                                        ->defaultValue(array('presentation'))
                                                    ->end()
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('option_value')
                                ->isRequired()
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('model')->isRequired()->end()
                                    ->scalarNode('controller')->defaultValue('Sylius\Bundle\ResourceBundle\Controller\ResourceController')->end()
                                    ->scalarNode('repository')->cannotBeEmpty()->end()
                                    ->scalarNode('form')->defaultValue('Sylius\Bundle\VariationBundle\Form\Type\OptionValueType')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
