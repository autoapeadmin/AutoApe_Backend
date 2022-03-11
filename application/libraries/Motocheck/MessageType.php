<?php

class MessageType
{

  /**
   * 
   * @var string $_
   * @access public
   */
  public $_ = null;

  /**
   * 
   * @var string $Type
   * @access public
   */
  public $Type = null;

  /**
   * 
   * @var string $Code
   * @access public
   */
  public $Code = null;

  /**
   * 
   * @var string $Origin
   * @access public
   */
  public $Origin = null;

  /**
   * 
   * @var int $Line
   * @access public
   */
  public $Line = null;

  /**
   * 
   * @param string $_
   * @param string $Type
   * @param string $Code
   * @param string $Origin
   * @param int $Line
   * @access public
   */
  public function __construct($_, $Type, $Code, $Origin, $Line)
  {
    $this->_ = $_;
    $this->Type = $Type;
    $this->Code = $Code;
    $this->Origin = $Origin;
    $this->Line = $Line;
  }

}
