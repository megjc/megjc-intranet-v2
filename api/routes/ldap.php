<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Api\Helpers\LDAPConnection as LDAP;

$app->group('/v2/ldap', function(){
  /**
   * Authenticate a user based on credentials supplied.
   * If the user is found, a token in generated and issued.
   */
  $this->post('/search', function (Request $request, Response $response) {
      $req = $request->getParsedBody();
      $instance = LDAP::getInstance();
      if($instance->getConnection()){
        $results = $instance->search($req['q']);
        return $response->withJson($results, 200);
      }
  });

});
// ->add(function(Request $request, Response $response, $next){
//     $response->getBody()->write('It is now');
//     $response = $next($request, $response);
//     return $response;
// });
?>
