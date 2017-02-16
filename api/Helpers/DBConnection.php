<?php
  namespace Api\Helpers;
  use \Dotenv\Dotenv as Dotenv;
  use \PDO;
  /**
   *
   */
  class DBConnection{
    private $_dbname;
    private $_dbuser;
    private $_dbpass;
    private $_dbhost;
    private $_dbport;
    private static $_instance;
    /**
     * [__construct description]
     */
    private function __construct(){
      $dotenv = new Dotenv(__DIR__);
      $dotenv->load();
      $this->_dbname = getenv('DB_NAME');
      $this->_dbuser = getenv('DB_USER');
      $this->_dbpass = getenv('DB_PASS');
      $this->_dbhost = getenv('DB_HOST');
      $this->_dbport = getenv('DB_PORT');
      $this->_dbconn = new PDO('mysql:host='.$this->_dbhost.';port='.$this->_dbport.';dbname='.$this->_dbname, $this->_dbuser, $this->_dbpass);
      $this->_dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    /**
     * [getInstance description]
     * @return [type] [description]
     */
    public function getInstance(){
      if(!self::$_instance) self::$_instance = new self();
      return self::$_instance;
    }

    private function clone(){}

    public function getConnection(){
      return $this->_dbconn;
    }

    public function close(){
      if(self::$_instance) self::$_dbconn = null;
    }
  }

?>
