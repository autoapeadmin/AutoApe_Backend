<?php

class ClientIdentifiersType
{

  /**
   * 
   * @var string $UserName
   * @access public
   */
  public $UserName = null;

  /**
   * 
   * @var AccountId $AccountId
   * @access public
   */
  public $AccountId = null;

  /**
   * 
   * @var string $IPAddress
   * @access public
   */
  public $IPAddress = null;

  /**
   * 
   * @param string $UserName
   * @param AccountId $AccountId
   * @param string $IPAddress
   * @access public
   */
  public function __construct($UserName, $AccountId, $IPAddress)
  {
    $this->UserName = $UserName;
    $this->AccountId = $AccountId;
    $this->IPAddress = $IPAddress;
  }

}
