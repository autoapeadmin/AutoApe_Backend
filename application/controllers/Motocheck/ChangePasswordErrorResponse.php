<?php

class ChangePasswordErrorResponse
{

  /**
   * 
   * @var ChangePasswordErrorResponseResponseBody $ResponseBody
   * @access public
   */
  public $ResponseBody = null;

  /**
   * 
   * @param ChangePasswordErrorResponseResponseBody $ResponseBody
   * @access public
   */
  public function __construct($ResponseBody)
  {
    $this->ResponseBody = $ResponseBody;
  }

}
