<?php

namespace Api\Services;
use \Api\Helpers\LDAPConnection as LDAP;
use \Api\Models\User as User;
/**
 *
 */
class Authentication{
  /**
   * [authUser description]
   * @param  [type] $user [description]
   * @return [type]       [description]
   */
  public static function authUser($user){
    $authenticated = "authenticated";
    $instance = LDAP::getInstance();
    if($instance->getConnection()){
      $dn = $instance->bind($user['username'], $user['password']);
      if(!$dn){
        $authenticated = false;
      }else{
        $authenticated = User::getUserByDn($dn);
      }
    }
    $instance->close();
    return $authenticated;
  }
}
?>
