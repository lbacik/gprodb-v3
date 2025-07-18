<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    '@symfony/stimulus-bundle' => [
        'path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js',
    ],
    '@hotwired/turbo' => [
        'version' => '7.3.0',
    ],
    '@fortawesome/fontawesome-free' => [
        'version' => '6.5.1',
    ],
    '@fortawesome/fontawesome-free/css/fontawesome.min.css' => [
        'version' => '6.5.1',
        'type' => 'css',
    ],
    '@fortawesome/fontawesome-free/css/all.css' => [
        'version' => '6.5.1',
        'type' => 'css',
    ],
    'stimulus-use' => [
        'version' => '0.52.2',
    ],
    'stimulus-popover' => [
        'version' => '6.2.0',
    ],
    'altcha/dist/altcha.js' => [
        'version' => '1.2.0',
    ],
    'altcha/dist/altcha.i18n.js' => [
        'version' => '2.0.5',
    ],
];
