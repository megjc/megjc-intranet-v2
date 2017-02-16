<?php
  namespace Api\Models;
  use \Api\Helpers\DBConnection as DBConn;
  /**
   *
   */
  class Department{

    /**
     * Get a user by id
     * @param  [type] $id user's id
     * @return [type]     [description]
     */
    public function get( $id ='', $name='' ){
      $instance = DBConn::getInstance();
      if( $instance->getConnection() ){
        $conn = $instance->getConnection();
        $sql = "SELECT id, name FROM departments";
        try {
          $stmt = $conn->prepare( $sql );
          //$stmt->bindParam( "id", $id );
          $stmt->execute();
          $result = $stmt->fetch( \PDO::FETCH_OBJ );
        } catch ( \PDOException $e ) {
          $result = array( "error"=> $e->getMessage() );
        }
        return $result;
      }
    }
  }

?>
