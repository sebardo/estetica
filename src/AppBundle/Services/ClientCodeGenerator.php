<?php


namespace AppBundle\Services;


class ClientCodeGenerator
{
	const PREFIX = 'CET3';
	const FILLED = 0;
	const LENGTH = 4;

	public static function createNextCode($id)
	{
		return self::PREFIX . str_pad($id, self::LENGTH, self::FILLED, STR_PAD_LEFT);
	}
}