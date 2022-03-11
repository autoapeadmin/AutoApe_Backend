<?php

class CDIToken
{

  /**
   * 
   * @var string $TokenValue
   * @access public
   */
  public $TokenValue = null;

  /**
   * 
   * @var string $GroupName
   * @access public
   */
  public $GroupName = null;

  /**
   * 
   * @var string $FunctionName
   * @access public
   */
  public $FunctionName = null;

  /**
   * 
   * @var string $UserName
   * @access public
   */
  public $UserName = null;

  /**
   * 
   * @var string $Id
   * @access public
   */
  public $Id = null;

  /**
   * 
   * @param string $TokenValue
   * @param string $GroupName
   * @param string $FunctionName
   * @param string $UserName
   * @param string $Id
   * @access public
   */
  public function __construct($TokenValue, $GroupName, $FunctionName, $UserName, $Id)
  {
    $this->TokenValue = $TokenValue;
    $this->GroupName = $GroupName;
    $this->FunctionName = $FunctionName;
    $this->UserName = $UserName;
    $this->Id = $Id;
  }

}
