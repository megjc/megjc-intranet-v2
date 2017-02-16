<?php
use \Api\Middleware\JOTMiddleware;
use \Api\Middleware\Permission;

$app->group('/v2', function() use($container, $app){
  $app->group('/admin', function(){
    $this->post('/users', 'UserCtrl:create');
    $this->get('/users', 'UserCtrl:index');
    $this->get('/users/{id:[0-9]}', 'UserCtrl:show');
    $this->put('/users/{id:[0-9]}', 'UserCtrl:update');
    $this->delete('/users/{id:[0-9]}', 'UserCtrl:delete');
    $this->get('/ldap/search', 'LDAPCtrl:search');
  })->add(new Permission($container))->add(new JOTMiddleware($container));

  $this->post('/auth', 'AuthCtrl:authUser');

  $app->group('/token', function(){
    $this->get('/refresh', 'TokenCtrl:refresh');
    $this->get('/issue', 'TokenCtrl:issue');
    $this->get('/ack', 'TokenCtrl:ack');
  });

});

?>
