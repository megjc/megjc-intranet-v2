<?php
namespace Api\Middleware;

/**
 *
 */
class Middleware{

  protected $container;

  function __construct($container){
    $this->container = $container;
  }
}

?>
