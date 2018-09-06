<?php

namespace Artincms\LHS\Helpers\Classes;

class EncryptString
{
	var $SecretKey = "fe5f30d2c48d2285a399118831d3bc8e"; // you can change it
	var $key = 'my secret key';
	var $iv = '12345678';

	public function safe_b64encode($string)
	{
		$data = base64_encode($string);
		$data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
		return $data;
	}

	public function safe_b64decode($string)
	{
		$data = str_replace(array('-', '_'), array('+', '/'), $string);
		$mod4 = strlen($data) % 4;
		if ($mod4)
		{
			$data .= substr('====', $mod4);
		}
		return base64_decode($data);
	}

	public function encode($value)
	{
		if (!$value)
		{
			return false;
		}
		$cipher = mcrypt_module_open(MCRYPT_BLOWFISH, '', 'cbc', '');
		mcrypt_generic_init($cipher, $this->key, $this->iv);
		$encrypted = mcrypt_generic($cipher, $value);
		mcrypt_generic_deinit($cipher);
		return $this->safe_b64encode($encrypted);
	}

	/*$text = $value;
	   $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	   $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	   $CryptText = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->SecretKey, $text, MCRYPT_MODE_ECB, $iv);
	   return trim($this->safe_b64encode($CryptText));*/
	/*-------------------------------------------------*/

	public function decode($value)
	{
		if (!$value)
		{
			return false;
		}
		$value = $this->safe_b64decode($value);
		$cipher = mcrypt_module_open(MCRYPT_BLOWFISH, '', 'cbc', '');
		mcrypt_generic_init($cipher, $this->key, $this->iv);
		$decrypted = mdecrypt_generic($cipher, $value);
		mcrypt_generic_deinit($cipher);
		return $decrypted;
	}
	/*$CryptText = $this->safe_b64decode($value);
	$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	$DecryptText = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->SecretKey, $CryptText, MCRYPT_MODE_ECB, $iv);
	return trim($DecryptText);*/


	/* ******** Encode ******** */
//    public static function encode($pure_string, $encryption_key = FALSE)
//    {
//        if(!$encryption_key)
//        {
//            $encryption_key = "fe5f30d2c48d2285a399118831d3bc8e"; /*--mdf-Adel.Raheli*/
//        }
//        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
//        $iv      = mcrypt_create_iv($iv_size, MCRYPT_RAND);
//        $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, md5(base64_encode(trim($encryption_key))), utf8_encode(trim($pure_string)), MCRYPT_MODE_ECB, $iv);
//        return base64_encode($encrypted_string);
//    }

	/* ********* Decode ************ */
//    public static function decode($encrypted_string, $encryption_key = FALSE)
//    {
//        if(!$encryption_key)
//        {
//            $encryption_key = "fe5f30d2c48d2285a399118831d3bc8e"; /*--mdf-Adel.Raheli*/
//        }
//        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
//        $iv      = mcrypt_create_iv($iv_size, MCRYPT_RAND);
//        $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, md5(base64_encode(trim($encryption_key))),base64_decode(trim($encrypted_string)), MCRYPT_MODE_ECB, $iv);
//        return trim($decrypted_string);
//    }

}


