<?php

class AuthenticateClientResponse
{

  /**
   * 
   * @var anyType $ResponseBody
   * @access public
   */
  public $ResponseBody = null;

  /**
   * 
   * @param anyType $ResponseBody
   * @access public
   */
  public function __construct($ResponseBody)
  {
    $this->ResponseBody = $ResponseBody;
  }

}
