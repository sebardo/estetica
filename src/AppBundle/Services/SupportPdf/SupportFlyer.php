<?php


namespace AppBundle\Services\SupportPdf;


class SupportFlyer extends SupportPdf
{
	const SUPPORT_NAME = 'flyers';
	const FLYER_OBVERSE_HEADER = 'flyer-obverse-header';
	const FLYER_OBVERSE_HEADER_ALIGN = 'center';
	const FLYER_OBVERSE_HEADER_X = 0;
	const FLYER_OBVERSE_HEADER_Y = 100;
	const FLYER_OBVERSE_HEADER_SPACE_X = 0;
	const FLYER_OBVERSE_HEADER_SPACE_Y = 5;
	const FLYER_OBVERSE_HEADER_STYLE = SupportPdf::FONT_STYLE_BIG;
	const FLYER_OBVERSE_CONTENT_1 = 'flyer-obverse-content-1';
	const FLYER_OBVERSE_CONTENT_1_ALIGN = 'center';
	const FLYER_OBVERSE_CONTENT_1_X = 0;
	const FLYER_OBVERSE_CONTENT_1_Y = 160;
	const FLYER_OBVERSE_CONTENT_1_SPACE_X = 0;
	const FLYER_OBVERSE_CONTENT_1_SPACE_Y = 10;
	const FLYER_OBVERSE_CONTENT_1_STYLE = SupportPdf::FONT_STYLE_NORMAL;
	const FLYER_OBVERSE_PRIZE_1 = 'flyer-obverse-prize-1';
	const FLYER_OBVERSE_PRIZE_1_ALIGN = 'center';
	const FLYER_OBVERSE_PRIZE_1_X = 0;
	const FLYER_OBVERSE_PRIZE_1_Y = 70;
	const FLYER_OBVERSE_PRIZE_1_SPACE_X = 0;
	const FLYER_OBVERSE_PRIZE_1_SPACE_Y = 5;
	const FLYER_OBVERSE_PRIZE_1_STYLE = SupportPdf::FONT_STYLE_SMALL;
	const FLYER_OBVERSE_PRIZE_2 = 'flyer-obverse-prize-2';
	const FLYER_OBVERSE_PRIZE_2_ALIGN = 'center';
	const FLYER_OBVERSE_PRIZE_2_X = 0;
	const FLYER_OBVERSE_PRIZE_2_Y = 50;
	const FLYER_OBVERSE_PRIZE_2_SPACE_X = 0;
	const FLYER_OBVERSE_PRIZE_2_SPACE_Y = 200;
	const FLYER_OBVERSE_PRIZE_2_STYLE = SupportPdf::FONT_STYLE_SMALL;
	const FLYER_OBVERSE_CONTENT_2 = 'flyer-obverse-content-2';
	const FLYER_OBVERSE_CONTENT_2_ALIGN = 'center';
	const FLYER_OBVERSE_CONTENT_2_X = 0;
	const FLYER_OBVERSE_CONTENT_2_Y = 200;
	const FLYER_OBVERSE_CONTENT_2_SPACE_X = 0;
	const FLYER_OBVERSE_CONTENT_2_SPACE_Y = 5;
	const FLYER_OBVERSE_CONTENT_2_STYLE = SupportPdf::FONT_STYLE_NORMAL;
	const FLYER_OBVERSE_FOOTER = 'flyer-obverse-footer';
	const FLYER_OBVERSE_FOOTER_ALIGN = 'center';
	const FLYER_OBVERSE_FOOTER_X = 0;
	const FLYER_OBVERSE_FOOTER_Y = 70;
	const FLYER_OBVERSE_FOOTER_SPACE_X = 0;
	const FLYER_OBVERSE_FOOTER_SPACE_Y = 10;
	const FLYER_OBVERSE_FOOTER_STYLE = SupportPdf::FONT_STYLE_NORMAL;
	const FLYER_BACK_HEADER = 'flyer-back-header';
	const FLYER_BACK_HEADER_ALIGN = 'center';
	const FLYER_BACK_HEADER_X = 0;
	const FLYER_BACK_HEADER_Y = 100;
	const FLYER_BACK_HEADER_SPACE_X = 0;
	const FLYER_BACK_HEADER_SPACE_Y = 0;
	const FLYER_BACK_HEADER_STYLE = SupportPdf::FONT_STYLE_BIG;
	const FLYER_BACK_DESCRIPTION = 'flyer-back-description';
	const FLYER_BACK_DESCRIPTION_ALIGN = 'center';
	const FLYER_BACK_DESCRIPTION_X = 0;
	const FLYER_BACK_DESCRIPTION_Y = 500;
	const FLYER_BACK_DESCRIPTION_SPACE_X = 0;
	const FLYER_BACK_DESCRIPTION_SPACE_Y = 50;
	const FLYER_BACK_DESCRIPTION_STYLE = SupportPdf::FONT_STYLE_NORMAL;
	const FLYER_BACK_FOOTER = 'flyer-back-footer';
	const FLYER_BACK_FOOTER_ALIGN = 'center';
	const FLYER_BACK_FOOTER_X = 0;
	const FLYER_BACK_FOOTER_Y = 100;
	const FLYER_BACK_FOOTER_SPACE_X = 0;
	const FLYER_BACK_FOOTER_SPACE_Y = 280;
	const FLYER_BACK_FOOTER_STYLE = SupportPdf::FONT_STYLE_NORMAL;
	const PAGES = 2;

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
				self::FLYER_OBVERSE_HEADER => array(
					'x' => self::FLYER_OBVERSE_HEADER_X,
					'y' => self::FLYER_OBVERSE_HEADER_Y,
					'space_x' => self::FLYER_OBVERSE_HEADER_SPACE_X,
					'space_y' => self::FLYER_OBVERSE_HEADER_SPACE_Y,
					'style' => self::FLYER_OBVERSE_HEADER_STYLE,
					'align' => self::FLYER_OBVERSE_HEADER_ALIGN,
					'content' => $this->values[self::FLYER_OBVERSE_HEADER],
				),
				self::FLYER_OBVERSE_CONTENT_1 => array(
					'x' => self::FLYER_OBVERSE_CONTENT_1_X,
					'y' => self::FLYER_OBVERSE_CONTENT_1_Y,
					'space_x' => self::FLYER_OBVERSE_CONTENT_1_SPACE_X,
					'space_y' => self::FLYER_OBVERSE_CONTENT_1_SPACE_Y,
					'style' => self::FLYER_OBVERSE_CONTENT_1_STYLE,
					'align' => self::FLYER_OBVERSE_CONTENT_1_ALIGN,
					'content' => $this->values[self::FLYER_OBVERSE_CONTENT_1],
				),
				self::FLYER_OBVERSE_PRIZE_1 => array(
					'x' => self::FLYER_OBVERSE_PRIZE_1_X,
					'y' => self::FLYER_OBVERSE_PRIZE_1_Y,
					'space_x' => self::FLYER_OBVERSE_PRIZE_1_SPACE_X,
					'space_y' => self::FLYER_OBVERSE_PRIZE_1_SPACE_Y,
					'style' => self::FLYER_OBVERSE_PRIZE_1_STYLE,
					'align' => self::FLYER_OBVERSE_PRIZE_1_ALIGN,
					'content' => $this->values[self::FLYER_OBVERSE_PRIZE_1],
				),
				self::FLYER_OBVERSE_PRIZE_2 => array(
					'x' => self::FLYER_OBVERSE_PRIZE_2_X,
					'y' => self::FLYER_OBVERSE_PRIZE_2_Y,
					'space_x' => self::FLYER_OBVERSE_PRIZE_2_SPACE_X,
					'space_y' => self::FLYER_OBVERSE_PRIZE_2_SPACE_Y,
					'style' => self::FLYER_OBVERSE_PRIZE_2_STYLE,
					'align' => self::FLYER_OBVERSE_PRIZE_2_ALIGN,
					'content' => $this->values[self::FLYER_OBVERSE_PRIZE_2],
				),
				self::FLYER_OBVERSE_CONTENT_2 => array(
					'x' => self::FLYER_OBVERSE_CONTENT_2_X,
					'y' => self::FLYER_OBVERSE_CONTENT_2_Y,
					'space_x' => self::FLYER_OBVERSE_CONTENT_2_SPACE_X,
					'space_y' => self::FLYER_OBVERSE_CONTENT_2_SPACE_Y,
					'style' => self::FLYER_OBVERSE_CONTENT_2_STYLE,
					'align' => self::FLYER_OBVERSE_CONTENT_2_ALIGN,
					'content' => $this->values[self::FLYER_OBVERSE_CONTENT_2],
				),
				self::FLYER_OBVERSE_FOOTER => array(
					'x' => self::FLYER_OBVERSE_FOOTER_X,
					'y' => self::FLYER_OBVERSE_FOOTER_Y,
					'space_x' => self::FLYER_OBVERSE_FOOTER_SPACE_X,
					'space_y' => self::FLYER_OBVERSE_FOOTER_SPACE_Y,
					'style' => self::FLYER_OBVERSE_FOOTER_STYLE,
					'align' => self::FLYER_OBVERSE_FOOTER_ALIGN,
					'content' => $this->values[self::FLYER_OBVERSE_FOOTER],
				)
			),
			array(
				self::FLYER_BACK_HEADER => array(
					'x' => self::FLYER_BACK_HEADER_X,
					'y' => self::FLYER_BACK_HEADER_Y,
					'space_x' => self::FLYER_BACK_HEADER_SPACE_X,
					'space_y' => self::FLYER_BACK_HEADER_SPACE_Y,
					'style' => self::FLYER_BACK_HEADER_STYLE,
					'align' => self::FLYER_BACK_HEADER_ALIGN,
					'content' => $this->values[self::FLYER_BACK_HEADER],
				),
				self::FLYER_BACK_DESCRIPTION => array(
					'x' => self::FLYER_BACK_DESCRIPTION_X,
					'y' => self::FLYER_BACK_DESCRIPTION_Y,
					'space_x' => self::FLYER_BACK_DESCRIPTION_SPACE_X,
					'space_y' => self::FLYER_BACK_DESCRIPTION_SPACE_Y,
					'style' => self::FLYER_BACK_DESCRIPTION_STYLE,
					'align' => self::FLYER_BACK_DESCRIPTION_ALIGN,
					'content' => $this->values[self::FLYER_BACK_DESCRIPTION],
				),
				self::FLYER_BACK_FOOTER => array(
					'x' => self::FLYER_BACK_FOOTER_X,
					'y' => self::FLYER_BACK_FOOTER_Y,
					'space_x' => self::FLYER_BACK_FOOTER_SPACE_X,
					'space_y' => self::FLYER_BACK_FOOTER_SPACE_Y,
					'style' => self::FLYER_BACK_FOOTER_STYLE,
					'align' => self::FLYER_BACK_FOOTER_ALIGN,
					'content' => $this->values[self::FLYER_BACK_FOOTER],
				),
			)
		);
	}

	public function getContent()
	{
		return $this->contents;
	}
}