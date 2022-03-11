<?php

class ChangePasswordRequest
{

  /**
   * 
   * @var ChangePasswordRequestRequestBody $RequestBody
   * @access public
   */
  public $RequestBody = null;

  /**
   * 
   * @param ChangePasswordRequestRequestBody $RequestBody
   * @access public
   */
  public function __construct($RequestBody)
  {
    $this->RequestBody = $RequestBody;
  }

}
