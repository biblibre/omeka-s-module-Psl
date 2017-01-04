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
                'oai_dc_psl' => 'Psl\Service\OaiMetadataFormat\OaiDcPslFactory',
            ],
        ],
    ],
];
