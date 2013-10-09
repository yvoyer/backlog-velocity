#!/usr/bin/env php
<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

$root = dirname(__DIR__);
require_once $root . '/vendor/autoload.php';

use Doctrine\ORM\Tools\Setup;
use Star\Component\Sprint\BacklogApplication;

$isDevMode = true;
// $entityFolder = __DIR__ . '/Entity';
// $config = Setup::createAnnotationMetadataConfiguration(array($entityFolder), $isDevMode);
$config = Setup::createXMLMetadataConfiguration(array($root . '/config/doctrine'), $isDevMode);

$output = new \Symfony\Component\Console\Output\ConsoleOutput();

$conn = array(
    'dbname'   => 'backlog_local',
    'user'     => 'username',
    'password' => 'password',
    'host'     => 'localhost',
    'driver'   => 'pdo_mysql',
//    'driver' => 'pdo_sqlite',
//    'path'   => $root . '/backlog.sqlite',
);

$dialogHelper = new \Symfony\Component\Console\Helper\DialogHelper();
$console = new BacklogApplication($conn, $config, $dialogHelper, $output);
$console->run(null, $output);