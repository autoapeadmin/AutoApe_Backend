<?php

class ChangePasswordRequestRequestBody
{

  /**
   * 
   * @var string $UserIdentifier
   * @access public
   */
  public $UserIdentifier = null;

  /**
   * 
   * @var string $CurrentPassword
   * @access public
   */
  public $CurrentPassword = null;

  /**
   * 
   * @var string $NewPassword
   * @access public
   */
  public $NewPassword = null;

  /**
   * 
   * @param string $UserIdentifier
   * @param string $CurrentPassword
   * @param string $NewPassword
   * @access public
   */
  public function __construct($UserIdentifier, $CurrentPassword, $NewPassword)
  {
    $this->UserIdentifier = $UserIdentifier;
    $this->CurrentPassword = $CurrentPassword;
    $this->NewPassword = $NewPassword;
  }

}
