<?php

class IdentificationType
{

  /**
   * 
   * @var CdiReferenceType $CdiReference
   * @access public
   */
  public $CdiReference = null;

  /**
   * 
   * @param CdiReferenceType $CdiReference
   * @access public
   */
  public function __construct($CdiReference)
  {
    $this->CdiReference = $CdiReference;
  }

}
