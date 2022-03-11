<?php

class AuthenticateClientRequest
{

  /**
   * 
   * @var anyType $RequestBody
   * @access public
   */
  public $RequestBody = null;

  /**
   * 
   * @param anyType $RequestBody
   * @access public
   */
  public function __construct($RequestBody)
  {
    $this->RequestBody = $RequestBody;
  }

}
