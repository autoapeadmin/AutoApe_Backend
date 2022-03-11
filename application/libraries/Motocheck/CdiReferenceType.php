<?php

class CdiReferenceType
{

  /**
   * 
   * @var string $MessageId
   * @access public
   */
  public $MessageId = null;

  /**
   * 
   * @var EnvironmentType $Environment
   * @access public
   */
  public $Environment = null;

  /**
   * 
   * @var CdiReferenceTypeFinancialSession $FinancialSession
   * @access public
   */
  public $FinancialSession = null;

  /**
   * 
   * @param string $MessageId
   * @param EnvironmentType $Environment
   * @param CdiReferenceTypeFinancialSession $FinancialSession
   * @access public
   */
  public function __construct($MessageId, $Environment, $FinancialSession)
  {
    $this->MessageId = $MessageId;
    $this->Environment = $Environment;
    $this->FinancialSession = $FinancialSession;
  }

}
