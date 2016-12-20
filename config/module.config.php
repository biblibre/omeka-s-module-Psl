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
];
