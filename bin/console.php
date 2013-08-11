#!/usr/bin/env php
<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

$root = dirname(__DIR__);
require_once $root . '/vendor/autoload.php';

use Star\Component\Sprint\Command\Team\AddCommand as TeamAddCommand;
use Star\Component\Sprint\Repository\Team\YamlFileRepository;
use Symfony\Component\Console\Application;

$console = new Application();
$console->add(new TeamAddCommand(new YamlFileRepository($root, 'teams')));

$console->run();