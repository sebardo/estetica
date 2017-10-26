<?php


namespace AppBundle\Services;

use AppBundle\Services\SupportPdf\SupportPdf;
use AppBundle\Services\SupportPdf\SupportPdfInterface;
use AppBundle\Services\SupportPdf\SupportRollup;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Image;

class Pdf
{
	const ORIENTATION_PORTRAIT = 'P';
	const ORIENTATION_LANDSCAPE = 'L';
	const UNIT = 'pt';
	const FONT_TYPE = 'Arial';
	const FONT_WEIGHT = 'B';
	const FONT_SIZE = '16';
	const MARGIN_X = 5;
	const MARGIN_Y = 5;
	private $width;
	private $height;
	private $logoPath;

	public function __construct($width = '210', $height = '297', $logoPath)
	{
		$this->width = $width;
		$this->height = $height;
		$this->logoPath = $logoPath;
	}

	public function getWidth($withMargin = true)
	{
		if($withMargin){
			return $this->width - self::MARGIN_X;
		}

		return $this->width;
	}

	public function getHeight($withMargin = true)
	{
		if($withMargin) {
			return $this->height - self::MARGIN_Y;
		}

		return $this->height;
	}

	public function getSize($withMargin = true)
	{
		return array(
			'w' => $this->getWidth($withMargin),
			'y' => $this->getHeight($withMargin)
		);
	}

	private function getOrientation()
	{
		if($this->getWidth(false) > $this->getHeight(false)){
			return self::ORIENTATION_LANDSCAPE;
		}

		return self::ORIENTATION_PORTRAIT;
	}

	public static function generatePdf($supportType, $contentValues, $supportBackgroundImages, $bgImageAttributes, $logoPath, $filename = null)
	{
		$supportPdf = SupportPdf::create($supportType, $contentValues, $supportBackgroundImages);
		$pdf = new Pdf($bgImageAttributes['width'], $bgImageAttributes['height'], $logoPath);

		return $pdf->generate($supportPdf, $filename);
	}

	private function generate(SupportPdfInterface $support, $filename = null)
	{
		$pdf = new \FPDF($this->getOrientation(), self::UNIT, array($this->getWidth(false), $this->getHeight(false)));
		$page = 0;
		//Content
		foreach($support->getContent() as $pageContent) {
			$pdf->AddPage();
			$pdf->SetFont(self::FONT_TYPE, self::FONT_WEIGHT, self::FONT_SIZE);
			//Background Image
			$this->generateBgImage($page, $pdf, $support);

			if($page == 0){
				//Logo Header
				$this->generateLogo($pdf);
			}

			$this->generateContentElement($pageContent, $pdf);
			$page++;
		}

		if(empty($filename)) {
			return new Response($pdf->Output(), 200, array(
			'Content-Type' => 'application/pdf'));
		}

		return $pdf->Output($filename, 'F');
	}

	private function calculateCenter($imageWidth)
	{
		return ($imageWidth > $this->getWidth()) ? round(abs($this->calculateWidthMax($imageWidth))/2, 2) : round(abs($this->getWidth() - $imageWidth)/2, 2);
	}

	private function calculateWidthMax($imageWidth)
	{
		return ($imageWidth > $this->getWidth()) ? $this->getWidth() : $imageWidth;
	}

	private function generateBgImage($pageNumber, \FPDF $pdf, SupportPdfInterface $support)
	{
		$backgroundImage = $support->getBackgroundImageByPage($pageNumber);
		$backgroundImageAttributes = ImageHandler::getImageSize($backgroundImage);
		$pdf->Image($backgroundImage, 0, 0, $backgroundImageAttributes['width'],$backgroundImageAttributes['height']);
	}

	private function generateThumbnailPath($imagePath)
	{
		$pathArray = explode('/', $imagePath);
		$pathArraySize = count($pathArray);
		$pathArray[$pathArraySize - 1] = 'thumbail_' . $pathArray[$pathArraySize - 1];

		return implode('/', $pathArray);
	}

	private function generateLogo(\FPDF $pdf)
	{
		$logoPath = $this->logoPath;
		$logoAttributes = ImageHandler::getImageSize($logoPath);
		if($logoAttributes['width'] > ImageHandler::THUMBNAIL_IMAGE_MAX_WIDTH || $logoAttributes['height'] > ImageHandler::THUMBNAIL_IMAGE_MAX_HEIGHT){
			$_logoPath = $logoPath;
			$logoPath = $this->generateThumbnailPath($logoPath);
			$logoThumbnail = ImageHandler::generateImageThumbnail($_logoPath, $logoPath);
			$logoAttributes = ImageHandler::getImageSize($logoPath);
		}

		$pdf->Image($logoPath, $this->calculateCenter($logoAttributes['width']), null, $this->calculateWidthMax($logoAttributes['width']));
	}

	private function generateContentElement($pageContent, \FPDF $pdf)
	{
		foreach ($pageContent as $attributes) {
			$pdf->SetFont(self::FONT_TYPE, null, $attributes['style']);
			$pdf->Ln($attributes['space_y']);
			$str = $attributes['content'];
			$text = iconv('UTF-8', 'windows-1252', $str);

			if($attributes['space_x'] > 0) {
				$pdf->SetXY($attributes['space_x'], $attributes['space_y']);
				$pdf->MultiCell(0, SupportRollup::LINE_HEIGHT, $text, 0, SupportPdf::formattingAlign($attributes['x'], false));
			}else {
				$pdf->MultiCell(0, SupportRollup::LINE_HEIGHT, $text, 0, SupportPdf::formattingAlign($attributes['align']), false);
			}
		}
	}
}