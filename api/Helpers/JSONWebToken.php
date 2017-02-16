<?php
  namespace Api\Helpers;
  use \Firebase\JWT\JWT;
  use \Dotenv\Dotenv;
  /**
   *
   */
  class JSONWebToken{
    /**
     * [encode description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public static function encode( $data ){
      self::_loadEnv();
      $token = array("iat" => time(),
                    "data" => $data,
                    "exp" => time() + getenv('JWT_EXPIRY') );
      return JWT::encode( $token, getenv('JWT_KEY') );
    }
    /**
     * [decode description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public static function decode( $data ){
      $result = "";
      self::_loadEnv();
      try {
        $decoded = JWT::decode( $data, getenv('JWT_KEY'), array('HS256') );
        $result = ( array ) $decoded;
      } catch (\Exception $e) {
        return array("status" => 403, "developer message" => $e->getMessage(), "message" => "Unauthorized" );
      }
      return $result;
    }
    /**
     * [_loadEnv description]
     * @return [type] [description]
     */
    private function _loadEnv(){
      $dotenv = new Dotenv(__DIR__);
      $dotenv->load();
    }
  }

?>
