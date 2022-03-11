<?php

class StatusType
{

  /**
   * 
   * @var MessageType[] $Messages
   * @access public
   */
  public $Messages = null;

  /**
   * 
   * @var string $Code
   * @access public
   */
  public $Code = null;

  /**
   * 
   * @var string $Text
   * @access public
   */
  public $Text = null;

  /**
   * 
   * @param MessageType[] $Messages
   * @param string $Code
   * @param string $Text
   * @access public
   */
  public function __construct($Messages, $Code, $Text)
  {
    $this->Messages = $Messages;
    $this->Code = $Code;
    $this->Text = $Text;
  }

}
