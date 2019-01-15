<?php

$fromBeginPackConfig = __DIR__.'/beginningPack.php';
$mainjsPath = __DIR__.'/../resources/js/jquery/main.js';
$mainajaxjsPath = __DIR__.'/../resources/js/jquery/main-ajax.js';
$appPath = __DIR__.'/../resources/js/app.js';
$mainStorePath = __DIR__.'/../resources/js/store/mainStore.js';
$storeIndexPath = __DIR__.'/../resources/js/store/index.js';
$globalMixinPath = __DIR__.'/../resources/js/globalMixin.js';
$stablePublicComponents = __DIR__.'/../resources/js/components';
$beginningPackScss = __DIR__.'/../resources/sass/beginningPacks';
$beginningPackCss = __DIR__.'/../resources/sass/css';
$beginningPackCssPlugins = __DIR__.'/../resources/sass/plugins';

return [
    /**
     * Path of files published
     */
    'beginningPack' => [
        $fromBeginPackConfig => config_path('beginningPack.php')
    ],
    'mainjs' => [
        $mainjsPath => resource_path('js/jquery/main.js')
    ],
    'mainajaxjs' => [
        $mainajaxjsPath => resource_path('js/jquery/main-ajax.js')
    ],
    'appjs' => [
        $appPath => resource_path('js/app.js')
    ],
    'mainStorejs' => [
        $mainStorePath => resource_path('js/store/mainStore.js')
    ],
    'mainStoreIndexjs' => [
        $storeIndexPath => resource_path('js/store/index.js')
    ],
    'globalMixinjs' => [
        $globalMixinPath => resource_path('js/globalMixin.js')
    ],
    'beginningPackScss' => [
        $beginningPackScss => resource_path('sass/beginningPacks'),
        $beginningPackCss => resource_path('sass/css'),
        $beginningPackCssPlugins => resource_path('sass/plugins')
    ],
    'stablePublicComponents' => [
        $stablePublicComponents => resource_path('js/components'),
    ],
    'scriptSnippets' => [
        $mainjsPath => resource_path('js/jquery/main.js'),
        $mainajaxjsPath => resource_path('js/jquery/main-ajax.js'),
        $appPath => resource_path('js/app.js'),
        $mainStorePath => resource_path('js/store/mainStore.js'),
        $storeIndexPath => resource_path('js/store/index.js'),
        $globalMixinPath => resource_path('js/globalMixin.js'),
        $beginningPackScss => resource_path('sass/beginningPacks'),
        $beginningPackCss => resource_path('sass/css'),
        $beginningPackCssPlugins => resource_path('sass/plugins'),
        $stablePublicComponents => resource_path('js/components'),
    ]
];
