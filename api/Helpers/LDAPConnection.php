<?php
  namespace Api\Helpers;
  use \Dotenv\Dotenv as Dotenv;
  /**
   *
   */
  class LDAPConnection{
    private static $_instance;
    private $_ldaphost;
    private $_ldapport;
    private $_ldapconn;
    private $_ldapadmin;
    private $_ldappass;
    private $_ldaprootdn;

    /**
     * [__construct description]
     */
    private function __construct(){
      $dotenv = new Dotenv(__DIR__);
      $dotenv->load();
      $this->_ldaphost = "ldap://" . getenv('LDAP_HOST');
      $this->_ldapport = getenv('LDAP_PORT');
      $this->_ldapadmin = getenv('LDAP_ADMIN');
      $this->_ldappass = getenv('LDAP_PASS');
      $this->_ldaprootdn = getenv('LDAP_ROOT_DN');
      $this->_ldapconn = ldap_connect($this->_ldaphost, $this->_ldapport);
      ldap_set_option($this->_ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
      ldap_set_option($this->_ldapconn, LDAP_OPT_REFERRALS, 0);
    }
    /**
     * [getInstance description]
     * @return [type] [description]
     */
    public function getInstance(){
      if(!self::$_instance) self::$_instance = new self();
      return self::$_instance;
    }

    /**
     * [clone description]
     * @return [type] [description]
     */
    private function clone(){}
    /**
     * [getConnection description]
     * @return [type] [description]
     */
    public function getConnection(){
      return $this->_ldapconn;
    }
    /**
     * [close description]
     * @return [type] [description]
     */
    public function close(){
      ldap_close($this->_ldapconn);
      self::$_instance = null;
    }
    /**
     * [_bind description]
     * @param  [type] $username [description]
     * @param  [type] $password [description]
     * @return [type]           [description]
     */
    private function _bind($username, $password){
      return @ldap_bind($this->_ldapconn, $username, $password);
    }
    /**
     * [bind description]
     * @param  [type] $username [description]
     * @param  [type] $password [description]
     * @return [type]           [description]
     */
    public function bind($username, $password){
      $dn = false;
      $admin_bind = $this->_bind($this->_ldapadmin, $this->_ldappass);
      if($admin_bind){
        $user_bind = $this->_bind($username, $password);
        if($user_bind){
          $filter = "(|(name=$username*)(samaccountname=$username)(sn=$username*))";
          $results = ldap_search($this->_ldapconn, $this->_ldaprootdn, $filter);
          $first = ldap_first_entry($this->_ldapconn, $results);
          $dn = ldap_get_dn($this->_ldapconn, $first);
          }
        }
        return $dn;
      }
      /**
       * [search description]
       * @param  [type] $query [description]
       * @return [type]        [description]
       */
      public function search($query){
        $admin_bind = $this->_bind($this->_ldapadmin, $this->_ldappass);
        if($admin_bind){
            $filter = "(|(name=$query*)(samaccountname=$query)(sn=$query*))";
            $results = ldap_search($this->_ldapconn, $this->_ldaprootdn, $filter);
            $entries = ldap_get_entries($this->_ldapconn, $results);
            $names = array();
            for($i=0;$i<$entries['count']; $i++){
              list($cn, $ou, $company, $dc, $dc_two, $dc_three) = explode(',', $entries[$i]['dn']);
              $department = explode("=", $ou);
              array_push($names, array("name"=> $entries[$i]['displayname'][0], "department"=> $department[1], "dn" => $entries[$i]['dn'] ));
            }
          }
          return $names;
      }
  }
?>
