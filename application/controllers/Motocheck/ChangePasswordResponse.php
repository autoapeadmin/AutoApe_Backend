<?php

class ChangePasswordResponse
{

  /**
   * 
   * @var ChangePasswordErrorResponse $ChangePasswordErrorResponse
   * @access public
   */
  public $ChangePasswordErrorResponse = null;

  /**
   * 
   * @var ChangePasswordNormalResponse $ChangePasswordNormalResponse
   * @access public
   */
  public $ChangePasswordNormalResponse = null;

  /**
   * 
   * @param ChangePasswordErrorResponse $ChangePasswordErrorResponse
   * @param ChangePasswordNormalResponse $ChangePasswordNormalResponse
   * @access public
   */
  public function __construct($ChangePasswordErrorResponse, $ChangePasswordNormalResponse)
  {
    $this->ChangePasswordErrorResponse = $ChangePasswordErrorResponse;
    $this->ChangePasswordNormalResponse = $ChangePasswordNormalResponse;
  }

}
