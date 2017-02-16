<?php
// use \Psr\Http\Message\ServerRequestInterface as Request;
// use \Psr\Http\Message\ResponseInterface as Response;
use \Api\Models\User as User;

$app->group('/v2/users', function(){
  /**
   * Authenticate a user based on credentials supplied.
   * If the user is found, a token in generated and issued.
   */
  $this->get('', function ( $request, $response) {
    $users = User::getUsers();
    return $response->withJson($users, 200);
  });

});
// ->add(function(Request $request, Response $response, $next){
//     $response->getBody()->write('It is now');
//     $response = $next($request, $response);
//     return $response;
// });
?>
