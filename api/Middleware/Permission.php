<?php
namespace Api\Middleware;

/**
 *
 */
class Permission extends Middleware
{

  public function __invoke($request, $response, $next){
    $user = $request->getAttribute('user');
    if(is_object($user)){
      if($user->type_id !== "1"){
        return $response->withJson(array("success"=>false, "developer message"=>"Forbidden"),403);
      }
    }else{
      return $response->withJson(array("success" => false, "developer message"=>"Bad Request."), 400);
    }
    $response = $next($request, $response);
    return $response;
  }
}

?>
