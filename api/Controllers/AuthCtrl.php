<?php
namespace Api\Controllers;
use \Api\Helpers\LDAPConnection as LDAP;
use \Api\Models\User as User;
use \Api\Helpers\JSONWebToken as JOT;
/**
 * Authorizes a user based on provided credentials
 */
class AuthCtrl extends BaseCtrl{
  /**
   * [authUser description]
   * @param  [type] $request  [description]
   * @param  [type] $response [description]
   * @return [type]           [description]
   */
  public function authUser($request, $response){
    $message = array("success" => false, "message"=>"Bad Request. User credentials invalid");
    $user = $request->getParsedBody();
    $instance = LDAP::getInstance();
    if($instance->getConnection()){
      $dn = $instance->bind($user['username'], $user['password']);
      if(!$dn){
        return $response->withJson($message,400);
      }
      $authenticated = User::getUserByDn($dn);
      if(!$authenticated || $authenticated === null){
          return $response->withJson($message, 400);
      }
      $encoded = JOT::encode($authenticated);
    }else{
      $message['message'] = "Internal server error";
      return $response->withJson($message, 500);
    }
    $instance->close();
    return $response->withJson(array("token"=>$encoded),200);
  }
}
?>
