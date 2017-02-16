<?php

namespace Api\Controllers;
use \Api\Helpers\JSONWebToken as JOT;
/**
 *
 */
class TokenCtrl extends BaseCtrl
{

  public function issue($request, $response){
    return $response->withJson('Issue token');
  }

  public function refresh($request, $response){
    return $response->withJson('Refresh token');
  }

  public function ack($request, $response){
    if($request->getHeader('Authorization')){
      $auth_header = $request->getHeaderLine('Authorization');
      $token = explode(" ", $auth_header);
      $decoded = JOT::decode( $token[1] );
      if(array_key_exists('status', $decoded)){
        return $response->withJson($decoded, $decoded['status']);
      }else{
        return $response->withJson(array('success'=>true));
      }
    }else{
      return $response->withJson(array("success" => false,
                                      "developer message"=>"Bad Request.",
                                      "message"=> "Authorization header missing."), 400);
    }
  }

}


?>
