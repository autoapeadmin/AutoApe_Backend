<?php

class SecurityTokenReference
{

  /**
   * 
   * @var string $Id
   * @access public
   */
  public $Id = null;

  /**
   * 
   * @param string $Id
   * @access public
   */
  public function __construct($Id)
  {
    $this->Id = $Id;
  }

}
