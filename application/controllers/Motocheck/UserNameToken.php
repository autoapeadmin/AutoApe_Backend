<?php

class UserNameToken
{

  /**
   * 
   * @var string $UserName
   * @access public
   */
  public $UserName = null;

  /**
   * 
   * @var string $GroupName
   * @access public
   */
  public $GroupName = null;

  /**
   * 
   * @var string $Password
   * @access public
   */
  public $Password = null;

  /**
   * 
   * @var string $LocationID
   * @access public
   */
  public $LocationID = null;

  /**
   * 
   * @var string $EndPointID
   * @access public
   */
  public $EndPointID = null;

  /**
   * 
   * @param string $UserName
   * @param string $GroupName
   * @param string $Password
   * @param string $LocationID
   * @param string $EndPointID
   * @access public
   */
  public function __construct($UserName, $GroupName, $Password, $LocationID, $EndPointID)
  {
    $this->UserName = $UserName;
    $this->GroupName = $GroupName;
    $this->Password = $Password;
    $this->LocationID = $LocationID;
    $this->EndPointID = $EndPointID;
  }

}
