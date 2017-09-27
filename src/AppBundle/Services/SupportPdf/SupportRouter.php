<?php


namespace AppBundle\Services\SupportPdf;


class SupportRouter extends SupportPdf
{
	const SUPPORT_NAME = 'routers';
	const ROUTER_OBVERSE_HEADER = 'router-obverse-header';
	const ROUTER_OBVERSE_HEADER_X = 'center';
	const ROUTER_OBVERSE_HEADER_Y = 250;
	const ROUTER_OBVERSE_HEADER_SPACE_X = 0;
	const ROUTER_OBVERSE_HEADER_SPACE_Y = 150;
	const ROUTER_OBVERSE_HEADER_STYLE = self::FONT_STYLE_BIG;
	const ROUTER_OBVERSE_CONTENT_1 = 'router-obverse-content-1';
	const ROUTER_OBVERSE_CONTENT_1_X = 'center';
	const ROUTER_OBVERSE_CONTENT_1_Y = 350;
	const ROUTER_OBVERSE_CONTENT_1_SPACE_X = 0;
	const ROUTER_OBVERSE_CONTENT_1_SPACE_Y = 70;
	const ROUTER_OBVERSE_CONTENT_1_STYLE = self::FONT_STYLE_NORMAL;
	const ROUTER_OBVERSE_PRIZE_1 = 'router-obverse-prize-1';
	const ROUTER_OBVERSE_PRIZE_1_X = 'center';
	const ROUTER_OBVERSE_PRIZE_1_Y = 150;
	const ROUTER_OBVERSE_PRIZE_1_SPACE_X = 0;
	const ROUTER_OBVERSE_PRIZE_1_SPACE_Y = 10;
	const ROUTER_OBVERSE_PRIZE_1_STYLE = self::FONT_STYLE_SMALL;
	const ROUTER_OBVERSE_PRIZE_2 = 'router-obverse-prize-2';
	const ROUTER_OBVERSE_PRIZE_2_X = 'center';
	const ROUTER_OBVERSE_PRIZE_2_Y = 150;
	const ROUTER_OBVERSE_PRIZE_2_SPACE_X = 0;
	const ROUTER_OBVERSE_PRIZE_2_SPACE_Y = 400;
	const ROUTER_OBVERSE_PRIZE_2_STYLE = self::FONT_STYLE_SMALL;
	const ROUTER_OBVERSE_CONTENT_2 = 'router-obverse-content-2';
	const ROUTER_OBVERSE_CONTENT_2_X = 'center';
	const ROUTER_OBVERSE_CONTENT_2_Y = 350;
	const ROUTER_OBVERSE_CONTENT_2_SPACE_X = 0;
	const ROUTER_OBVERSE_CONTENT_2_SPACE_Y = 10;
	const ROUTER_OBVERSE_CONTENT_2_STYLE = self::FONT_STYLE_NORMAL;
	const ROUTER_OBVERSE_FOOTER = 'router-obverse-footer';
	const ROUTER_OBVERSE_FOOTER_X = 'center';
	const ROUTER_OBVERSE_FOOTER_Y = 200;
	const ROUTER_OBVERSE_FOOTER_SPACE_X = 0;
	const ROUTER_OBVERSE_FOOTER_SPACE_Y = 150;
	const ROUTER_OBVERSE_FOOTER_STYLE = self::FONT_STYLE_NORMAL;
	const PAGES = 1;

	protected $pages;
	protected $contents = array();

	public function __construct($values, $backgroundImage = array())
	{
		parent::__construct($values, $backgroundImage);
		$this->contents = $this->generateContent();
		$this->pages = self::PAGES;
	}

	private function generateContent()
	{
		return array(
			array(
				self::ROUTER_OBVERSE_HEADER => array(
					'x' => self::ROUTER_OBVERSE_HEADER_X,
					'y' => self::ROUTER_OBVERSE_HEADER_Y,
					'space_x' => self::ROUTER_OBVERSE_HEADER_SPACE_X,
					'space_y' => self::ROUTER_OBVERSE_HEADER_SPACE_Y,
					'style' => self::ROUTER_OBVERSE_HEADER_STYLE,
					'content' => $this->values[self::ROUTER_OBVERSE_HEADER],
				),
				self::ROUTER_OBVERSE_CONTENT_1 => array(
					'x' => self::ROUTER_OBVERSE_CONTENT_1_X,
					'y' => self::ROUTER_OBVERSE_CONTENT_1_Y,
					'space_x' => self::ROUTER_OBVERSE_CONTENT_1_SPACE_X,
					'space_y' => self::ROUTER_OBVERSE_CONTENT_1_SPACE_Y,
					'style' => self::ROUTER_OBVERSE_CONTENT_1_STYLE,
					'content' => $this->values[self::ROUTER_OBVERSE_CONTENT_1],
				),
				self::ROUTER_OBVERSE_PRIZE_1 => array(
					'x' => self::ROUTER_OBVERSE_PRIZE_1_X,
					'y' => self::ROUTER_OBVERSE_PRIZE_1_Y,
					'space_x' => self::ROUTER_OBVERSE_PRIZE_1_SPACE_X,
					'space_y' => self::ROUTER_OBVERSE_PRIZE_1_SPACE_Y,
					'style' => self::ROUTER_OBVERSE_PRIZE_1_STYLE,
					'content' => $this->values[self::ROUTER_OBVERSE_PRIZE_1],
				),
				self::ROUTER_OBVERSE_PRIZE_2 => array(
					'x' => self::ROUTER_OBVERSE_PRIZE_2_X,
					'y' => self::ROUTER_OBVERSE_PRIZE_2_Y,
					'space_x' => self::ROUTER_OBVERSE_PRIZE_2_SPACE_X,
					'space_y' => self::ROUTER_OBVERSE_PRIZE_2_SPACE_Y,
					'style' => self::ROUTER_OBVERSE_PRIZE_2_STYLE,
					'content' => $this->values[self::ROUTER_OBVERSE_PRIZE_2],
				),
				self::ROUTER_OBVERSE_CONTENT_2 => array(
					'x' => self::ROUTER_OBVERSE_CONTENT_2_X,
					'y' => self::ROUTER_OBVERSE_CONTENT_2_Y,
					'space_x' => self::ROUTER_OBVERSE_CONTENT_2_SPACE_X,
					'space_y' => self::ROUTER_OBVERSE_CONTENT_2_SPACE_Y,
					'style' => self::ROUTER_OBVERSE_CONTENT_2_STYLE,
					'content' => $this->values[self::ROUTER_OBVERSE_CONTENT_2],
				),
				self::ROUTER_OBVERSE_FOOTER => array(
					'x' => self::ROUTER_OBVERSE_FOOTER_X,
					'y' => self::ROUTER_OBVERSE_FOOTER_Y,
					'space_x' => self::ROUTER_OBVERSE_FOOTER_SPACE_X,
					'space_y' => self::ROUTER_OBVERSE_FOOTER_SPACE_Y,
					'style' => self::ROUTER_OBVERSE_FOOTER_STYLE,
					'content' => $this->values[self::ROUTER_OBVERSE_FOOTER],
				)
			)
		);
	}

	public function getContent()
	{
		return $this->contents;
	}
}