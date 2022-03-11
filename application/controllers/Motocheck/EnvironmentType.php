<?php

class EnvironmentType
{

  /**
   * 
   * @var string $Code
   * @access public
   */
  public $Code = null;

  /**
   * 
   * @var string $Release
   * @access public
   */
  public $Release = null;

  /**
   * 
   * @param string $Code
   * @param string $Release
   * @access public
   */
  public function __construct($Code, $Release)
  {
    $this->Code = $Code;
    $this->Release = $Release;
  }

}
