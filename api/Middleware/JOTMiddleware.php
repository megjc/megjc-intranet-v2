<?php

namespace Api\Middleware;
use \Api\Helpers\JSONWebToken as JOT;
/**
 * Middleware handling JWT authentication
 * for routes.
 */
class JOTMiddleware extends Middleware
{

  public function __invoke($request, $response, $next){
    if($request->getHeader('Authorization')){
      $auth_header = $request->getHeaderLine('Authorization');
      $token = explode(" ", $auth_header);
      $decoded = JOT::decode( $token[1] );
      if(array_key_exists('status', $decoded)){
        return $response->withJson($decoded, $decoded['status']);
      }
      $request = $request->withAttribute('user', $decoded['data']);
    }else{
      return $response->withJson(array("success" => false,
                                      "developer message"=>"Bad Request.",
                                      "message"=> "Authorization header missing."), 400);
    }
    $response = $next($request, $response);
    return $response;
  }
}

?>
