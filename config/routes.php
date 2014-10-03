<?php

return [                          // Route definition
            [

                'name'              => 'module',
                'route'             => 'module [<name>]',
                'description'       => 'Create new module',
                'short_description' => 'Create new module',
                'handler'           => array($this, 'createModule')
            ],
            [

                'name'              => 'controller',
                'route'             => 'controller <module> [<name>]',
                'description'       => 'Create new controller',
                'short_description' => 'Create new controller',
                'defaults'          => [
                    'name' => 'IndexController',
                ],
                'handler'           => array($this, 'createController')
            ],
            [

                'name'              => 'route',
                'route'             => 'route <module> <path> <controller> <action>',
                'description'       => 'Create new route',
                'short_description' => 'Create new route',
                'defaults'          => [
                ],
                'handler'           => array($this, 'createRoute'),
            ],
            [

                'name'              => 'view',
                'route'             => 'view <module> <controller> <action>',
                'description'       => 'Create new view template',
                'short_description' => 'Create new view template',
                'defaults'          => [
                ],
                'handler'           => array($this, 'createView'),
            ],
            [

                'name'              => 'action',
                'route'             => 'action <controller_class> <action>',
                'description'       => 'Create new action',
                'short_description' => 'Create new action',
                'defaults'          => [
                ],
                'handler'           => array($this, 'createAction'),
            ]
        ];