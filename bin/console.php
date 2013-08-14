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

$console = new BacklogApplication($root);
$console->run();