<?php

/**
 * Class for isms.com.my
 *
 * @package isms
 * @author  Hariadi Hinta <diperakui@yahoo.com>
 * @link    https://github.com/hariadi/isms
 * @license MIT
 * @version 0.1.0
 */

namespace Isms;

class isms
{
	const VERSION  = '0.1.0';
	const HOST     = 'http://isms.com.my/';
	const SEND     = 'isms_sendsend.php?';
	const BALANCE  = 'isms_balance.php?';
	const SCHEDULE = 'isms_scheduler.php?';

	private $_login;
	private $_password;
	private $_auth;
	protected $to = array();
	protected $response_code = array(
		'2000' => 'SUCCESS - Message Sent.',
		'-1000' => 'UNKNOWN ERROR - Unknown error. Please contact the administrator.',
		'-1001' => 'AUTHENTICATION FAILED - Your username or password are incorrect.',
		'-1002' => 'ACCOUNT SUSPENDED / EXPIRED - Your account has been expired or suspended. Please contact the administrator.',
		'-1003' => 'IP NOT ALLOWED - Your IP is not allowed to send SMS. Please contact the administrator.',
		'-1004' => 'INSUFFICIENT CREDITS - You have run our of credits. Please reload your credits.',
		'-1005' => 'INVALID SMS TYPE - Your SMS type is not supported.',
		'-1006' => 'INVALID BODY LENGTH (1-900) - Your SMS body has exceed the length. Max = 900',
		'-1007' => 'INVALID HEX BODY - Your Hex body format is wrong.',
		'-1008' => 'MISSING PARAMETER - One or more required parameters are missing.'
	);

	public function  __construct( $login = null, $pwd = null )
	{
		$this->_login = $login;
		$this->_password = $pwd;
		$this->_auth = $this->getAuthParams();
	}

	public function addNumber($number)
  {
    return $this->addAnNumber($number);
  }

  public function getNumber()
  {
    return $this->_to;
  }

  public function normalize($number)
  {
    return $this->normalizeNumber($number);
  }

	public function send( $dstno, $msg, $type = 1, $sender = 63633 )
	{
		$url = self::HOST . self::SEND;
		$params = $this->_auth;

		if ( $dstno ) {
			$params['dstno'] = is_array($dstno) ? $this->formatNumber($dstno) : $dstno;
		}

		if ( $msg ) {
			$params['msg'] = $msg;
		}

		$params['type'] = $type;
		$params['sendid'] = $sender;

		$result = $this->curl( $url, $params );

		$response = array();
		$response['code'] = getInfo($result);
		$response['description'] = $this->getAnswer( $response['code'] );

		return $response;
	}

	public function balance()
	{
		$url = self::HOST . self::BALANCE;
		$params = $this->_auth;
		$result = $this->curl( $url, $params );
		return getInfo($result);
	}

	private function addAnNumber($number)
	{
		$number = $this->normalizeNumber($number);
		$this->_to[] = $number;
	}

	private function normalizeNumber($number, $countryCode = 60)
  {
  	if (isset($number)) {
  		$number = trim($number);
  		$number = str_replace("+", "", $number);
  		preg_match( '/(0|\+?\d{2})(\d{8,9})/', $number, $matches);
			if ((int) $matches[1] === 0 ) {
				$number = $countryCode . $matches[2];
			}
  	}
    return $number;
  }

  private function formatNumber($number)
	{
		$format = "";
		if (is_array($number)) {
			$format = implode(";", $number);
		}
		return $format;
	}

	private function getInfo($result)
	{
		return preg_replace("/[^0-9.-]/", "", $result);
	}

	private function getAuthParams()
	{
		$params['login'] = $this->_login;
		$params['password'] = $this->_password;
		return $params;
	}

	private function getAnswer( $code )
	{
		if ( isset( $this->response_code[$code] ) ) {
			return $this->response_code[$code];
		}
	}

	private function curl( $url, $params = array() )
	{
		// Use SSL: http://www.php.net/manual/en/function.curl-setopt-array.php#89850
		$ch = curl_init();
		$options = array(
    	CURLOPT_RETURNTRANSFER => TRUE,
    	CURLOPT_URL => $link,
    	CURLOPT_HEADER         => false,
    	CURLOPT_ENCODING       => "",
    	CURLOPT_POST            => 1,
			CURLOPT_POSTFIELDS => $params,
			CURLOPT_SSL_VERIFYHOST => 0,
    	CURLOPT_SSL_VERIFYPEER => false,
		);
		curl_setopt_array( $ch, $options );
		$result = curl_exec( $ch );
		curl_close( $ch );

		return $result;
	}
}