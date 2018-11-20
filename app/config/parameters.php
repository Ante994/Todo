<?php

$db = parse_url(getenv('CLEARDB_DATABASE_URL'));

$container->setParameter('database_driver', 'pdo_mysql');
$container->setParameter('database_host', 'localhost');
$container->setParameter('database_port', '');
$container->setParameter('database_name', substr($db["path"], 1));
$container->setParameter('database_user', 'root');
$container->setParameter('database_password', '');
$container->setParameter('secret', getenv('SECRET'));
$container->setParameter('locale', 'en');
$container->setParameter('mailer_transport', null);
$container->setParameter('mailer_host', null);
$container->setParameter('mailer_user', 'a@net.com');
$container->setParameter('mailer_password', null);