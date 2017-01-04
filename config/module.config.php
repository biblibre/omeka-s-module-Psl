<?php

return [
    'listeners' => [
        'Psl\MvcListeners',
    ],
    'service_manager' => [
        'invokables' => [
            'Psl\MvcListeners' => 'Psl\Mvc\MvcListeners',
        ],
    ],
    'oaipmhrepository' => [
        'metadata_formats' => [
            'factories' => [
                'psl_dc' => 'Psl\Service\OaiMetadataFormat\PslDcFactory',
            ],
        ],
    ],
];
