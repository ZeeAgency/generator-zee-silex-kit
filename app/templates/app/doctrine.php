#!/usr/bin/env php
<?php

$app = require __DIR__.'/core.php';

Doctrine\ORM\Tools\Console\ConsoleRunner::run(new Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($app['orm.em']->getConnection()),
    'em' => new Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($app['orm.em']),
)));
