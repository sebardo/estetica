<?php


namespace AppBundle\Services\SupportPdf;


interface SupportPdfInterface
{
	public function getBackgroundImageByPage($page);
	public function getContent();
}