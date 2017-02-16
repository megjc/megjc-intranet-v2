<?php
namespace Api\Controllers;

/**
 *
 */
class BaseCtrl{

  protected $container;

  function __construct($container){
    $this->container = $container;
  }
}

?>
