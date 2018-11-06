<?php
namespace Psl;

return [
    'listeners' => [
        'Psl\MvcListeners',
    ],
    'service_manager' => [
        'invokables' => [
            'Psl\MvcListeners' => Mvc\MvcListeners::class,
        ],
    ],
    'oaipmhrepository' => [
        'metadata_formats' => [
            'factories' => [
                'psl_dc' => Service\OaiPmh\Metadata\PslDcFactory::class,
            ],
        ],
    ],
];
