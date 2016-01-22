<?php
/*
 * Test Bootstrap
 */

/** @see https://github.com/Codeception/AspectMock */
include __DIR__.'/../vendor/autoload.php'; // composer autoload

$kernel = \AspectMock\Kernel::getInstance();
$kernel->init([
    'debug' => true,
    'includePaths' => [
        __DIR__.'/../src',
    ]
]);
