#!/usr/bin/env php
<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace {
    $root = dirname(__DIR__);
    require_once $root . '/vendor/autoload.php';

    use Star\Component\Sprint\BacklogApplication;
    use Star\Plugin\Doctrine\DoctrinePlugin;

    $applicationConfiguration = array(
        'database' => array(
            'dbname'   => 'backlog_local',
            'user'     => 'username',
            'password' => 'password',
            'host'     => 'localhost',
            'driver'   => 'pdo_mysql',
        ),
        'root' => $root,
        'env'  => 'dev',
        //    'driver' => 'pdo_sqlite',
        //    'path'   => $root . '/backlog.sqlite',
    );

    $output = new \Symfony\Component\Console\Output\ConsoleOutput();

    $console = new BacklogApplication($applicationConfiguration);
    $console->registerPlugin(new DoctrinePlugin($output));
    $console->run(null, $output);
}
