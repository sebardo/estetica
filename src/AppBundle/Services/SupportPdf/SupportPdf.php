<?php


namespace AppBundle\Services\SupportPdf;


use AppBundle\Entity\Creativity;

abstract class SupportPdf implements SupportPdfInterface
{
	const FONT_STYLE_NORMAL = '16';
	const FONT_STYLE_BIG = '24';
	const FONT_STYLE_SMALL = '12';
	const FONT_WEIGHT_NORMAL = 'normal';
	const FONT_WEIGHT_BOLD = 'bold';
	const FONT_WEIGHT_LIGHT = 'light';
	const FONT_ALIGN_CENTER = 'center';
	const FONT_ALIGN_LEFT = 'left';
	const FONT_ALIGN_RIGHT = 'right';
	const POSITION_LEFT = 'left';
	const POSITION_RIGHT = 'right';
	const POSITION_TOP = 'top';
	const POSITION_BOTTOM = 'bottom';
	const POSITION_CENTER = 'center';

	protected $values;
	protected $backgroundImage;

	public function __construct($values, $backgroundImage = array())
	{
		$this->values = $values;
		$this->backgroundImage = $backgroundImage;
	}

	public static function create($supportType, $values, $backgroundImage)
	{
		switch($supportType) {
			case Creativity::SUPPORT_FLYERS:
				$support = new SupportFlyer($values, $backgroundImage);
				break;
			case Creativity::SUPPORT_ROUTERS:
				$support = new SupportRouter($values, $backgroundImage);
				break;
			case Creativity::SUPPORT_ROLLUP:
				$support = new SupportRollup($values, $backgroundImage);
				break;
			default:
				$support = new SupportFlyer($values, $backgroundImage);
		}

		return $support;
	}

	public function getContent()
	{
		return array();
	}

	public function getBackgroundImageByPage($page)
	{
		return (array_key_exists($page, $this->backgroundImage)) ? $this->backgroundImage[$page] : null;
	}

	public static function formattingPosition($position, $globalSize = array('w' => 0, 'y' => 0))
	{
		if(!is_int($position)) {
			$position = self::segregatePosition($position, $globalSize);
		}

		return $position;
	}

	private static function segregatePosition($positionType, $size)
	{
		$response = 0;
		switch($positionType){
			case self::POSITION_CENTER:
				$response = round($size['w']/2, 2);
				break;
			case self::POSITION_LEFT:
				$response = 0;
				break;
			case self::POSITION_RIGHT:
				$response = round($size['w'], 2);
				break;
			case self::POSITION_TOP:
				$response = 0;
				break;
			case self::POSITION_BOTTOM:
				$response = round($size['y'], 2);
				break;
		}

		return $response;
	}

	public static function formattingAlign($align)
	{
		$response = 0;
		switch($align){
			case self::FONT_ALIGN_CENTER:
				$response = 'C';
				break;
			case self::FONT_ALIGN_LEFT:
				$response = 'L';
				break;
			case self::FONT_ALIGN_RIGHT:
				$response = 'R';
				break;
		}

		return $response;
	}
}