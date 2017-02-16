<?php
namespace Api\Controllers;
use \Api\Helpers\LDAPConnection as LDAP;
/**
 *
 */
class LDAPCtrl extends BaseCtrl{
  
  public function search($request, $response){
    $query = $request->getQueryParams();
    $instance = LDAP::getInstance();
    if($instance->getConnection()){
       if(array_key_exists("q", $query)){
        $results = $instance->search($query['q']);
        return $response->withJson($results);
      }
      return $response->withJson(array());
  }else{
    return $response->withJson(array("success"=>false, "message"=>"Internal server error"),500);
  }
 }
}
?>
