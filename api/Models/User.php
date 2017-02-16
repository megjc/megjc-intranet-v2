<?php
  namespace Api\Models;
  use \Api\Helpers\DBConnection as DBConn;
  use \Api\Models\Department;
  /**
   *
   */
  class User{
    /**
     * Find or creates a user
     * @param  [type] $new_user [description]
     * @return [type]           [description]
     */
    public function findOrCreate($request){
      if(array_key_exists('dn', $request)){
        if($request['dn'] === "") return array("status"=>400, "message"=>"Bad request");
        $user = self::getUserByDn($request['dn']);
        if($user === false || array_key_exists('error', $user)){
          $dept = Department::get(null, $request['department']);
          if(array_key_exists('error', $dept) || $dept === false){
            return array('status' => 500, "message" => "Internal error occurred" );
          }
          $new_user = array("dn"=> $request['dn'], "uname"=> $request['name'], "dept_id"=>intval($dept->id), "active"=>1, "type_id"=>2);
          $user = self::createUser($new_user);
          return array("status"=>200, "message"=>"User created", "id"=> $user);
        }else{
          return array("status"=> 409, "message"=>"User already exists");
        }
      }else{
        return array("status"=>404, "message"=>"Not found");
      }
    }

    private function createUser($user){
      $instance = DBConn::getInstance();
      if( $instance->getConnection() ){
        $conn = $instance->getConnection();
        $sql = "INSERT INTO users (dn, uname, dept_id, active, type_id)
                VALUES (:dn, :uname, :dept_id, :active, :type_id)";
        try {
          $stmt = $conn->prepare( $sql );
          $stmt->bindParam( "dn", $user['dn'] );
          $stmt->bindParam( "uname", $user['uname'] );
          $stmt->bindParam( "dept_id", $user['dept_id'] );
          $stmt->bindParam( "active", $user['active'] );
          $stmt->bindParam( "type_id", $user['type_id'] );
          $stmt->execute();
          $id = $conn->lastInsertId();
          //$result = $stmt->fetch( \PDO::FETCH_OBJ );
        } catch ( \PDOException $e ) {
          $result = array( "error"=> $e->getMessage() );
        }
        return $id;
      }
    }
    /**
     * Get a user by id
     * @param  [type] $id user's id
     * @return [type]     [description]
     */
    public function getUser( $id ){
      $instance = DBConn::getInstance();
      if( $instance->getConnection() ){
        $conn = $instance->getConnection();
        $sql = "SELECT id, dept_id, uname, active, type_id FROM users WHERE id=:id";
        try {
          $stmt = $conn->prepare( $sql );
          $stmt->bindParam( "id", $id );
          $stmt->execute();
          $result = $stmt->fetch( \PDO::FETCH_OBJ );
        } catch ( \PDOException $e ) {
          $result = array( "error"=> $e->getMessage() );
        }
        return $result;
      }
    }

    /**
     * [_getUserByDn description]
     * @param  [type] $dn [description]
     * @return [type]     [description]
     */
    public function getUserByDn($dn){
      $instance = DBConn::getInstance();
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
    /**
     * Get a list of all users
     * @return [type] [description]
     */
    public function getUsers(){
      $instance = DBConn::getInstance();
      if($instance->getConnection()){
        $conn = $instance->getConnection();
        $sql = "SELECT u.id,
                       u.uname as name,
                       IF(u.active = 1, 'true', 'false') as active,
                       d.name as department,
                       ut.title as type
                       FROM users as u
                       INNER JOIN departments as d
                       ON u.dept_id = d.id
                       INNER JOIN usertypes as ut
                       ON u.type_id = ut.id
                       ORDER BY department ASC";
        try {
          $stmt = $conn->prepare( $sql );
          $stmt->execute();
          $users = $stmt->fetchall(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
          $users = array("error"=> $e->getMessage());
        }
        return $users;
      }
    }
  }

?>
