<?php

class ChangePasswordNormalResponse
{

  /**
   * 
   * @var ChangePasswordNormalResponseResponseBody $ResponseBody
   * @access public
   */
  public $ResponseBody = null;

  /**
   * 
   * @param ChangePasswordNormalResponseResponseBody $ResponseBody
   * @access public
   */
  public function __construct($ResponseBody)
  {
    $this->ResponseBody = $ResponseBody;
  }

}
