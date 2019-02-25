# yii2-admin

Provides a layout and some generic functionality helpful for building admin areas

To set up theme, in config.php:
```
    'layout' => '@bvb/admin/views/layouts/main.php',
    'components' => [
        'view' => [
            'class' => \bvb\admin\web\View::class,
            'assetBundle' => \backend\assets\AppAsset::class, // --- Includes admin's AppAsset in dependency so we can add custom css
            'params' => [
                // --- Populates the side navigation
                'sideNav' =>  require('sideNav.php')
                // --- Populates the top navigation
                'topNavBar' =>  require('topNavBar.php')
            ]
        ]
    ],
```