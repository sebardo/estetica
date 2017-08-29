<?php


namespace AppBundle\Services;


class RandomString
{
	static public function getRandomPassword($length = 10) {
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass = array();
		$alphaLength = strlen($alphabet) - 1;
		for ($i = 0; $i < $length; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}

		return implode($pass);
	}

	static public function generateRandomString($length = 10) {
		return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	}
}