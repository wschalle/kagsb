<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Symfony\Component\Console\Helper\HelperSet;
$helperSet = new HelperSet(array(
    'db' => new ConnectionHelper($EntityManager->getConnection()),
    'em' => new EntityManagerHelper($EntityManager)
));
