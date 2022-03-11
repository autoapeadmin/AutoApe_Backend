<?php

class ServiceHeaderType
{

  /**
   * 
   * @var IdentificationType $Identification
   * @access public
   */
  public $Identification = null;

  /**
   * 
   * @var StatusType $Status
   * @access public
   */
  public $Status = null;

  /**
   * 
   * @var NextServiceType $NextService
   * @access public
   */
  public $NextService = null;

  /**
   * 
   * @param IdentificationType $Identification
   * @param StatusType $Status
   * @param NextServiceType $NextService
   * @access public
   */
  public function __construct($Identification, $Status, $NextService)
  {
    $this->Identification = $Identification;
    $this->Status = $Status;
    $this->NextService = $NextService;
  }

}
