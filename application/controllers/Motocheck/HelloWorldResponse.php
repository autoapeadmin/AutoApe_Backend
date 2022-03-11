<?php

class HelloWorldResponse
{

  /**
   * 
   * @var string $HelloWorldResult
   * @access public
   */
  public $HelloWorldResult = null;

  /**
   * 
   * @param string $HelloWorldResult
   * @access public
   */
  public function __construct($HelloWorldResult)
  {
    $this->HelloWorldResult = $HelloWorldResult;
  }

}
