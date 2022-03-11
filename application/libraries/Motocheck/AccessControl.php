<?php

include_once('AuthenticateClient.php');
include_once('AuthenticateClientResponse.php');
include_once('ChangePassword.php');
include_once('ChangePasswordResponse.php');
include_once('HelloWorld.php');
include_once('HelloWorldResponse.php');
include_once('AuthenticateClientRequest.php');
include_once('CDIRequest.php');
include_once('CDIResponse.php');
include_once('ServiceHeaderType.php');
include_once('IdentificationType.php');
include_once('CdiReferenceType.php');
include_once('EnvironmentType.php');
include_once('CdiReferenceTypeFinancialSession.php');
include_once('StatusType.php');
include_once('MessageType.php');
include_once('NextServiceType.php');
include_once('ClientIdentifiersType.php');
include_once('AccountId.php');
include_once('CDIToken.php');
include_once('Security.php');
include_once('UserNameToken.php');
include_once('SecurityTokenReference.php');
include_once('ChangePasswordRequest.php');
include_once('ChangePasswordRequestRequestBody.php');
include_once('ChangePasswordNormalResponse.php');
include_once('ChangePasswordNormalResponseResponseBody.php');
include_once('ChangePasswordErrorResponse.php');
include_once('ChangePasswordErrorResponseResponseBody.php');


/**
 * 
 */
class AccessControl extends \SoapClient
{

  /**
   * 
   * @var array $classmap The defined classes
   * @access private
   */
  private static $classmap = array(
    'AuthenticateClient' => '\AuthenticateClient',
    'AuthenticateClientResponse' => '\AuthenticateClientResponse',
    'ChangePassword' => '\ChangePassword',
    'ChangePasswordResponse' => '\ChangePasswordResponse',
    'HelloWorld' => '\HelloWorld',
    'HelloWorldResponse' => '\HelloWorldResponse',
    'AuthenticateClientRequest' => '\AuthenticateClientRequest',
    'AuthenticateClientResponse' => '\AuthenticateClientResponse',
    'CDIRequest' => '\CDIRequest',
    'CDIResponse' => '\CDIResponse',
    'ServiceHeaderType' => '\ServiceHeaderType',
    'IdentificationType' => '\IdentificationType',
    'CdiReferenceType' => '\CdiReferenceType',
    'EnvironmentType' => '\EnvironmentType',
    'CdiReferenceTypeFinancialSession' => '\CdiReferenceTypeFinancialSession',
    'StatusType' => '\StatusType',
    'MessageType' => '\MessageType',
    'NextServiceType' => '\NextServiceType',
    'ClientIdentifiersType' => '\ClientIdentifiersType',
    'AccountId' => '\AccountId',
    'CDIToken' => '\CDIToken',
    'Security' => '\Security',
    'UserNameToken' => '\UserNameToken',
    'SecurityTokenReference' => '\SecurityTokenReference',
    'ChangePasswordRequest' => '\ChangePasswordRequest',
    'ChangePasswordRequestRequestBody' => '\ChangePasswordRequestRequestBody',
    'ChangePasswordNormalResponse' => '\ChangePasswordNormalResponse',
    'ChangePasswordNormalResponseResponseBody' => '\ChangePasswordNormalResponseResponseBody',
    'ChangePasswordErrorResponse' => '\ChangePasswordErrorResponse',
    'ChangePasswordErrorResponseResponseBody' => '\ChangePasswordErrorResponseResponseBody',
    'ChangePasswordResponse' => '\ChangePasswordResponse');

  /**
   * 
   * @param array $options A array of config values
   * @param string $wsdl The wsdl file to use
   * @access public
   */
  public function __construct(array $options = array(), $wsdl = 'https://vt.services.nzta.govt.nz/cdtpt/webservices/security/accesscontrol.asmx?WSDL')
  {
    $arrContextOptions=stream_context_create(array(
      "ssl" => array(
           "verify_peer" => false,
           "verify_peer_name" => false, 'allow_self_signed' => true
      )));

    foreach (self::$classmap as $key => $value) {
      if (!isset($options['classmap'][$key])) {
        $options['classmap'][$key] = $value;
      }
    }
    
    parent::__construct($wsdl,  array(
      "trace"         => 1, 
      "exceptions"    => true, 
      "uri"           => "urn:xmethods-delayed-quotes",
      "style"         => SOAP_RPC,
      "use"           => SOAP_ENCODED,
      'soap_version' => 'SOAP_1_1',  // use soap 1.1 client
      'stream_context' => stream_context_create(array(
        'ssl' => array(
            'crypto_method' =>  STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT,
        )
     ))
    ) );
  }

  /**
   * 
   * @param AuthenticateClient $parameters
   * @access public
   * @return AuthenticateClientResponse
   */
  public function AuthenticateClient(AuthenticateClient $parameters)
  {
    return $this->__soapCall('AuthenticateClient', array($parameters));
  }

  /**
   * 
   * @param ChangePassword $parameters
   * @access public
   * @return ChangePasswordResponse
   */
  public function ChangePassword(ChangePassword $parameters)
  {
    return $this->__soapCall('ChangePassword', array($parameters));
  }

  /**
   * 
   * @param HelloWorld $parameters
   * @access public
   * @return HelloWorldResponse
   */
  public function HelloWorld(HelloWorld $parameters)
  {
    return $this->__soapCall('HelloWorld', array($parameters));
  }

}
