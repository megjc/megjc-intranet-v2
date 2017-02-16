<?php
namespace Api\Controllers;
use Api\Models\User as User;
/**
 *
 */
class UserCtrl extends BaseCtrl
{
  /**
   * Creates a new user.
   * @param  [type] $request  [description]
   * @param  [type] $response [description]
   * @return [type]           [description]
   */
  public function create($request, $response){
    $req = $request->getParsedBody();
    $user = User::findOrCreate($req);
    return $response->withJson($user, $user['status']);
  }

  public function update($request, $response, $args){
    return $response->withJson('update');
  }

  public function delete($request, $response, $args){
    return $response->withJson('delete');
  }
  /**
   * List all users if the user is an admin
   * @param  [type] $request  [description]
   * @param  [type] $response [description]
   * @return array           A list of users upon success, error message if
   *                         unsuccessful
   */
  public function index($request, $response){
    $users = User::getUsers();
    if(array_key_exists('error', $users) || $users === false){
      return $response->withJson(array("success"=>false,
                                      "developer message"=>"Internal error occurred"),
                                      500);
    }
    return $response->withJson($users);
  }
  /**
   * Shows a user by id
   * @param  [type] $request  [description]
   * @param  [type] $response [description]
   * @param  [type] $args     [description]
   * @return [type]           [description]
   */
  public function show($request, $response, $args){
    $user = User::getUser($args['id']);
    if(array_key_exists('error', $user) || $user === false){
      return $response->withJson(array("success"=>false,
                                      "developer message"=>"Internal error occurred"),
                                      500);
    }
    return $response->withJson($user);
  }

  /**
   * [_getUserByDn description]
   * @param  [type] $dn [description]
   * @return [type]     [description]
   */
  public function getUserByDn($dn){
    $instance = DBConnection::getInstance();
    if($instance->getConnection()){
      $conn = $instance->getConnection();
      $sql = "SELECT id, dept_id, uname, active, type_id FROM users WHERE dn=:dn";
      try {
        $stmt = $conn->prepare( $sql );
        $stmt->bindParam("dn", $dn);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_OBJ);
      } catch (\PDOException $e) {
        $result = array("error"=> $e->getMessage());
      }
      return $result;
    }
  }
}


?>
