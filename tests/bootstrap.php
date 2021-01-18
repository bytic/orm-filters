<?php

//use Mockery as m;
//use Nip\Cache\Stores\Repository;
//use Nip\Container\Container;
//use Nip\Database\Connections\Connection;
//use Symfony\Component\Cache\Adapter\FilesystemAdapter;

require dirname(__DIR__) . '/vendor/autoload.php';

define('PROJECT_BASE_PATH', __DIR__ . '/..');
define('TEST_BASE_PATH', __DIR__);
define('TEST_FIXTURE_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'fixtures');
//
//
//Container::setInstance(new Container());
////Container::getInstance()->set('db.connection', $connection);
//
//Container::getInstance()->set('inflector', new \Nip\Inflector\Inflector());
//
//$adapter = new FilesystemAdapter('', 600, TEST_FIXTURE_PATH . '/cache');
//$store = new Repository($adapter);
//$store->clear();
//Container::getInstance()->set('cache.store', $store);
