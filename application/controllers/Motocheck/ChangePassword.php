<?php

class ChangePassword
{

  /**
   * 
   * @var ChangePasswordRequest $ChangePasswordRequest
   * @access public
   */
  public $ChangePasswordRequest = null;

  /**
   * 
   * @param ChangePasswordRequest $ChangePasswordRequest
   * @access public
   */
  public function __construct($ChangePasswordRequest)
  {
    $this->ChangePasswordRequest = $ChangePasswordRequest;
  }

}
