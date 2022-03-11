<?php

class NextServiceType
{

  /**
   * 
   * @var string $Service
   * @access public
   */
  public $Service = null;

  /**
   * 
   * @var string $Method
   * @access public
   */
  public $Method = null;

  /**
   * 
   * @param string $Service
   * @param string $Method
   * @access public
   */
  public function __construct($Service, $Method)
  {
    $this->Service = $Service;
    $this->Method = $Method;
  }

}
