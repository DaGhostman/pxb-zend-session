<?php

return [
    'session' => [
        'handler' => \Zend\Session\SaveHandler\SaveHandlerInterface::class,
        'options' => [
            'name' => 'expressive',
            'cookie_lifetime' => 3600
        ],
        'validators' => []
    ]
];
