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

    public function generateFromHTML($html, $bgImageAttributes, $logoPath, $filename = null)
	{
        $pdf = new Pdf($bgImageAttributes['width'], $bgImageAttributes['height'], $logoPath);
        $fpdf = new PDF_HTML($pdf->getOrientation(), self::UNIT, array($pdf->getWidth(false), $pdf->getHeight(false)));
        
        $fpdf->AddPage();
        $fpdf->SetFont(self::FONT_TYPE, self::FONT_WEIGHT, self::FONT_SIZE);
        $fpdf->WriteHTML($html);
        $fpdf->Output($filename, 'I');
        die();
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
		$pathArray[$pathArraySize - 1] = 'thumbnail_' . $pathArray[$pathArraySize - 1];

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



class PDF_HTML extends \FPDF
{
    var $B=0;
    var $I=0;
    var $U=0;
    var $HREF='';
    var $ALIGN='';

    function WriteHTML($html)
    {
        //HTML parser
        $html=str_replace("\n",' ',$html);
        $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
        foreach($a as $i=>$e)
        {
            if($i%2==0)
            {
                //Text
                if($this->HREF)
                    $this->PutLink($this->HREF,$e);
                elseif($this->ALIGN=='center')
                    $this->Cell(0,5,$e,0,1,'C');
                else
                    $this->Write(5,$e);
            }
            else
            {
                //Tag
                if($e[0]=='/')
                    $this->CloseTag(strtoupper(substr($e,1)));
                else
                {
                    //Extract properties
                    $a2=explode(' ',$e);
                    $tag=strtoupper(array_shift($a2));
                    $prop=array();
                    foreach($a2 as $v)
                    {
                        if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                            $prop[strtoupper($a3[1])]=$a3[2];
                    }
                    $this->OpenTag($tag,$prop);
                }
            }
        }
    }

    function OpenTag($tag,$prop)
    {
        //Opening tag
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,true);
        if($tag=='A')
            $this->HREF=$prop['HREF'];
        if($tag=='BR')
            $this->Ln(5);
        if($tag=='P')
            $this->ALIGN=$prop['ALIGN'];
        if($tag=='HR')
        {
            if( !empty($prop['WIDTH']) )
                $Width = $prop['WIDTH'];
            else
                $Width = $this->w - $this->lMargin-$this->rMargin;
            $this->Ln(2);
            $x = $this->GetX();
            $y = $this->GetY();
            $this->SetLineWidth(0.4);
            $this->Line($x,$y,$x+$Width,$y);
            $this->SetLineWidth(0.2);
            $this->Ln(2);
        }
    }

    function CloseTag($tag)
    {
        //Closing tag
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,false);
        if($tag=='A')
            $this->HREF='';
        if($tag=='P')
            $this->ALIGN='';
    }

    function SetStyle($tag,$enable)
    {
        //Modify style and select corresponding font
        $this->$tag+=($enable ? 1 : -1);
        $style='';
        foreach(array('B','I','U') as $s)
            if($this->$s>0)
                $style.=$s;
        $this->SetFont('',$style);
    }

    function PutLink($URL,$txt)
    {
        //Put a hyperlink
        $this->SetTextColor(0,0,255);
        $this->SetStyle('U',true);
        $this->Write(5,$txt,$URL);
        $this->SetStyle('U',false);
        $this->SetTextColor(0);
    }
}