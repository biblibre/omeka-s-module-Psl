<?php

namespace Psl\Service\OaiMetadataFormat;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Psl\OaiMetadataFormat\OaiDcPsl;

class OaiDcPslFactory implements FactoryInterface
{
    /**
     * Create the media ingester manager service.
     *
     * @return Manager
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $settings = $container->get('Omeka\Settings');

        return new OaiDcPsl($settings);
    }
}
