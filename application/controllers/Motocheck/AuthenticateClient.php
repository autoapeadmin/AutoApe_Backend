<?php

class AuthenticateClient
{

  /**
   * 
   * @var AuthenticateClientRequest $AuthenticateClientRequest
   * @access public
   */
  public $AuthenticateClientRequest = null;

  /**
   * 
   * @param AuthenticateClientRequest $AuthenticateClientRequest
   * @access public
   */
  public function __construct($AuthenticateClientRequest)
  {
    $this->AuthenticateClientRequest = $AuthenticateClientRequest;
  }

}
