<?php


namespace AppBundle\Services\SupportPdf;


class SupportRollup extends SupportPdf
{
	const LINE_HEIGHT = 40;
	const SUPPORT_NAME = 'roll-up';
	const ROLLUP_OBVERSE_HEADER = 'rollup-obverse-header';
	const ROLLUP_OBVERSE_HEADER_ALIGN = 'right';
	const ROLLUP_OBVERSE_HEADER_X = 0;
	const ROLLUP_OBVERSE_HEADER_Y = 250;
	const ROLLUP_OBVERSE_HEADER_SPACE_X = 500;
	const ROLLUP_OBVERSE_HEADER_SPACE_Y = 500;
	const ROLLUP_OBVERSE_HEADER_STYLE = self::FONT_STYLE_BIG;
	const ROLLUP_OBVERSE_CONTENT_1 = 'rollup-obverse-content-1';
	const ROLLUP_OBVERSE_CONTENT_1_ALIGN = 'right';
	const ROLLUP_OBVERSE_CONTENT_1_X = 0;
	const ROLLUP_OBVERSE_CONTENT_1_Y = 300;
	const ROLLUP_OBVERSE_CONTENT_1_SPACE_X = 500;
	const ROLLUP_OBVERSE_CONTENT_1_SPACE_Y = self::ROLLUP_OBVERSE_HEADER_SPACE_Y + 450;
	const ROLLUP_OBVERSE_CONTENT_1_STYLE = self::FONT_STYLE_BIG;
	const ROLLUP_OBVERSE_CONTENT_2 = 'rollup-obverse-content-2';
	const ROLLUP_OBVERSE_CONTENT_2_ALIGN = 'right';
	const ROLLUP_OBVERSE_CONTENT_2_X = 0;
	const ROLLUP_OBVERSE_CONTENT_2_Y = 300;
	const ROLLUP_OBVERSE_CONTENT_2_SPACE_X = 500;
	const ROLLUP_OBVERSE_CONTENT_2_SPACE_Y = self::ROLLUP_OBVERSE_CONTENT_1_SPACE_Y + 450;
	const ROLLUP_OBVERSE_CONTENT_2_STYLE = self::FONT_STYLE_BIG;
	const ROLLUP_OBVERSE_CONTENT_3 = 'rollup-obverse-content-3';
	const ROLLUP_OBVERSE_CONTENT_3_ALIGN = 'right';
	const ROLLUP_OBVERSE_CONTENT_3_X = 0;
	const ROLLUP_OBVERSE_CONTENT_3_Y = 300;
	const ROLLUP_OBVERSE_CONTENT_3_SPACE_X = 500;
	const ROLLUP_OBVERSE_CONTENT_3_SPACE_Y = self::ROLLUP_OBVERSE_CONTENT_2_SPACE_Y + 450;
	const ROLLUP_OBVERSE_CONTENT_3_STYLE = self::FONT_STYLE_BIG;
	const ROLLUP_OBVERSE_FOOTER = 'rollup-obverse-footer';
	const ROLLUP_OBVERSE_FOOTER_ALIGN = 'center';
	const ROLLUP_OBVERSE_FOOTER_X = 'center';
	const ROLLUP_OBVERSE_FOOTER_Y = 100;
	const ROLLUP_OBVERSE_FOOTER_SPACE_X = 0;
	const ROLLUP_OBVERSE_FOOTER_SPACE_Y = 300;
	const ROLLUP_OBVERSE_FOOTER_STYLE = self::FONT_STYLE_BIG;
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
				self::ROLLUP_OBVERSE_HEADER => array(
					'x' => self::ROLLUP_OBVERSE_HEADER_X,
					'y' => self::ROLLUP_OBVERSE_HEADER_Y,
					'space_x' => self::ROLLUP_OBVERSE_HEADER_SPACE_X,
					'space_y' => self::ROLLUP_OBVERSE_HEADER_SPACE_Y,
					'style' => self::ROLLUP_OBVERSE_HEADER_STYLE,
					'align' => self::ROLLUP_OBVERSE_HEADER_ALIGN,
					'content' => $this->values[self::ROLLUP_OBVERSE_HEADER],
				),
				self::ROLLUP_OBVERSE_CONTENT_1 => array(
					'x' => self::ROLLUP_OBVERSE_CONTENT_1_X,
					'y' => self::ROLLUP_OBVERSE_CONTENT_1_Y,
					'space_x' => self::ROLLUP_OBVERSE_CONTENT_1_SPACE_X,
					'space_y' => self::ROLLUP_OBVERSE_CONTENT_1_SPACE_Y,
					'style' => self::ROLLUP_OBVERSE_CONTENT_1_STYLE,
					'align' => self::ROLLUP_OBVERSE_CONTENT_1_ALIGN,
					'content' => $this->values[self::ROLLUP_OBVERSE_CONTENT_1],
				),
				self::ROLLUP_OBVERSE_CONTENT_2 => array(
					'x' => self::ROLLUP_OBVERSE_CONTENT_2_X,
					'y' => self::ROLLUP_OBVERSE_CONTENT_2_Y,
					'space_x' => self::ROLLUP_OBVERSE_CONTENT_2_SPACE_X,
					'space_y' => self::ROLLUP_OBVERSE_CONTENT_2_SPACE_Y,
					'style' => self::ROLLUP_OBVERSE_CONTENT_2_STYLE,
					'align' => self::ROLLUP_OBVERSE_CONTENT_2_ALIGN,
					'content' => $this->values[self::ROLLUP_OBVERSE_CONTENT_2],
				),
				self::ROLLUP_OBVERSE_CONTENT_3 => array(
					'x' => self::ROLLUP_OBVERSE_CONTENT_3_X,
					'y' => self::ROLLUP_OBVERSE_CONTENT_3_Y,
					'space_x' => self::ROLLUP_OBVERSE_CONTENT_3_SPACE_X,
					'space_y' => self::ROLLUP_OBVERSE_CONTENT_3_SPACE_Y,
					'style' => self::ROLLUP_OBVERSE_CONTENT_3_STYLE,
					'align' => self::ROLLUP_OBVERSE_CONTENT_3_ALIGN,
					'content' => $this->values[self::ROLLUP_OBVERSE_CONTENT_3],
				),
				self::ROLLUP_OBVERSE_FOOTER => array(
					'x' => self::ROLLUP_OBVERSE_FOOTER_X,
					'y' => self::ROLLUP_OBVERSE_FOOTER_Y,
					'space_x' => self::ROLLUP_OBVERSE_FOOTER_SPACE_X,
					'space_y' => self::ROLLUP_OBVERSE_FOOTER_SPACE_Y,
					'style' => self::ROLLUP_OBVERSE_FOOTER_STYLE,
					'align' => self::ROLLUP_OBVERSE_FOOTER_ALIGN,
					'content' => $this->values[self::ROLLUP_OBVERSE_FOOTER],
				)
			)
		);
	}

	public function getContent()
	{
		return $this->contents;
	}
}