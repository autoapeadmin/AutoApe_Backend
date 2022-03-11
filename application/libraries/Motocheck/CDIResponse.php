<?php

class CDIResponse
{

  /**
   * 
   * @var ServiceHeaderType $ServiceHeader
   * @access public
   */
  public $ServiceHeader = null;

  /**
   * 
   * @param ServiceHeaderType $ServiceHeader
   * @access public
   */
  public function __construct($ServiceHeader)
  {
    $this->ServiceHeader = $ServiceHeader;
  }

}
