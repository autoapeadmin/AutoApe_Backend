<?php

class CdiReferenceTypeFinancialSession
{

  /**
   * 
   * @var string $_
   * @access public
   */
  public $_ = null;

  /**
   * 
   * @var string $Id
   * @access public
   */
  public $Id = null;

  /**
   * 
   * @var NMTOKEN $Status
   * @access public
   */
  public $Status = null;

  /**
   * 
   * @param string $_
   * @param string $Id
   * @param NMTOKEN $Status
   * @access public
   */
  public function __construct($_, $Id, $Status)
  {
    $this->_ = $_;
    $this->Id = $Id;
    $this->Status = $Status;
  }

}
