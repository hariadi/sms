# ISMS

[![Build Status](https://travis-ci.org/hariadi/isms.png)](https://travis-ci.org/hariadi/isms)

> PHP implementation of [ISMS](http://www.isms.com.my) Service

## Usage

Send SMS:

```php
//require_once 'src/Sms/isms.php';	// not required using composer
$sms = new \Sms\isms( $login, $password );
$sms->setMessage('Some message');
$sms->setNumber('Some number');
$sms->setNumber(array('number1', 'number2'));

$response = $sms->send();
var_dump($response);
```


Check Balance:

```php
//require_once 'src/Sms/isms.php';	// not required using composer
$sms = new \Sms\isms( $login, $password );

$response = $sms->balance();
var_dump($response);
```

Schedule SMS:

```php
$sms = new \Sms\isms( $login, $password );
$sms->setMessage('Some message');
$sms->setNumber('Some number');
$sms->setNumber(array('number1', 'number2'));
$sms->schedule($desc, $tr = 'onetime', $date, $hour, $min, $week = 1, $month = 1, $day = 1);

$response = $sms->send();
var_dump($response);
```

## Official Documentation

Documentation for the entire SMS API Integration can be found on the [ISMS website](http://www.isms.com.my/sms_api.php).

## Contributing

**All pull requests should be filed on the development branch [hariadi/isms](http://github.com/hariadi/isms) repository.**

## License

The ISMS PHP Library is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)