<?php
require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App([
  'settings' => [
    'displayErrorDetails' => true,
    'db' => [
      'driver' => 'mysql',
      'host' => 'localhost',
      'database' => 'staging',
      'username' => 'root',
      'password' => 'H1gh53cur1ty',
      'charset' => 'utf8',
      'collation' => 'utf8_unicode_ci',
      'prefix' => '',
    ]
  ],
]);

$container = $app->getContainer();

$container['db'] = function($container){
  $capsule = new \Illuminate\Database\Capsule\Manager;
  $capsule->addConnection($container['settings']['db']);
  $capsule->setAsGlobal();
  $capsule->bootEloquent();
  return $capsule;
};

$container['AuthCtrl'] = function($container){
  return new \Api\Controllers\AuthCtrl($container);
};

$container['UserCtrl'] = function($container){
  //$table = $container->get('db')->table('users');
  return new \Api\Controllers\UserCtrl($container);
};

$container['LDAPCtrl'] = function($container){
  return new \Api\Controllers\LDAPCtrl($container);
};

$container['TokenCtrl'] = function($container){
  return new \Api\Controllers\TokenCtrl($container);
};

require __DIR__ . '/../api/routes.php';

?>
