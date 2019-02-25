# yii2-admin

Provides a layout and some generic functionality helpful for building admin areas

To set up theme, in config.php:
    'components' => [
        'view' => [
            'theme' => [
                'pathMap' => [
                    // --- Use our admin theme
                    '@backend/views/layouts' => '@bvb/admin/views/layouts',
                ]
            ],
            'params' => [
                // --- Populates the side navigation
                'sideNav' =>  require('sidenav.php')
            ]
        ]
    ],