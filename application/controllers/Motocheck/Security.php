<?php

class Security
{

  /**
   * 
   * @var UserNameToken $UserNameToken
   * @access public
   */
  public $UserNameToken = null;

  /**
   * 
   * @var CDIToken $CDIToken
   * @access public
   */
  public $CDIToken = null;

  /**
   * 
   * @var string $CDISessionToken
   * @access public
   */
  public $CDISessionToken = null;

  /**
   * 
   * @var SecurityTokenReference $SecurityTokenReference
   * @access public
   */
  public $SecurityTokenReference = null;

  /**
   * 
   * @var ClientIdentifiersType $ClientIdentifiers
   * @access public
   */
  public $ClientIdentifiers = null;

  /**
   * 
   * @param UserNameToken $UserNameToken
   * @param CDIToken $CDIToken
   * @param string $CDISessionToken
   * @param SecurityTokenReference $SecurityTokenReference
   * @param ClientIdentifiersType $ClientIdentifiers
   * @access public
   */
  public function __construct($UserNameToken, $CDIToken, $CDISessionToken, $SecurityTokenReference, $ClientIdentifiers)
  {
    $this->UserNameToken = $UserNameToken;
    $this->CDIToken = $CDIToken;
    $this->CDISessionToken = $CDISessionToken;
    $this->SecurityTokenReference = $SecurityTokenReference;
    $this->ClientIdentifiers = $ClientIdentifiers;
  }

}
