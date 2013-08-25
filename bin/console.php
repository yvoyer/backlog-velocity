#!/usr/bin/env php
<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

$root = dirname(__DIR__);
require_once $root . '/vendor/autoload.php';

use Star\Component\Sprint\BacklogApplication;

$isDevMode = true;
// $entityFolder = __DIR__ . '/Entity';
// $config = Setup::createAnnotationMetadataConfiguration(array($entityFolder), $isDevMode);
$config = Setup::createXMLMetadataConfiguration(array($root . '/config/doctrine'), $isDevMode);

$conn = array(
    'driver' => 'pdo_sqlite',
    'path'   => $root . '/backlog.sqlite',
);

$console = new BacklogApplication($conn, $config);
$console->run();