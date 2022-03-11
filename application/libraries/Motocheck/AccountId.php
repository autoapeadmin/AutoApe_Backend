<?php

class AccountId
{

  /**
   * 
   * @var string $_
   * @access public
   */
  public $_ = null;

  /**
   * 
   * @var anonymous21 $AccountType
   * @access public
   */
  public $AccountType = null;

  /**
   * 
   * @param string $_
   * @param anonymous21 $AccountType
   * @access public
   */
  public function __construct($_, $AccountType)
  {
    $this->_ = $_;
    $this->AccountType = $AccountType;
  }

}
