<?php


namespace AppBundle\Services;


class ImageHandler
{
	const THUMBNAIL_IMAGE_MAX_WIDTH =  150;
	const THUMBNAIL_IMAGE_MAX_HEIGHT = 150;

	public static function imageCreateFromAny($filePath)
	{
		$type = exif_imagetype($filePath); // [] if you don't have exif you could use getImageSize()
		$allowedTypes = array(
			1,  // [] gif
			2,  // [] jpg
			3,  // [] png
		);
		
		if (!in_array($type, $allowedTypes)) {
			return false;
		}


		switch ($type) {
			case 1 :
				$im = imagecreatefromgif($filePath);
				break;
			case 2 :
				$im = imagecreatefromjpeg($filePath);
				break;
			case 3 :
				$im = imagecreatefrompng($filePath);
				break;
			default :
				$im = imagecreatefromjpeg($filePath);
				break;
		}

		return $im;
	}

	public static function getImageSize($filePath)
	{
		list($width, $height, $type, $attributes) = getimagesize($filePath);

		return array(
			'width' => $width,
			'height' => $height,
			'type' => $type,
			'attributes' => $attributes
		);
	}

	public static function generateImageThumbnail($sourceImagePath, $thumbnailImagePath, $thumbnailImageWidth = self::THUMBNAIL_IMAGE_MAX_WIDTH, $thumbnailImageHeight = self::THUMBNAIL_IMAGE_MAX_HEIGHT)
	{
		move_uploaded_file($sourceImagePath, $thumbnailImagePath);
		$sourceGdImage = self::imageCreateFromAny($sourceImagePath);
		if ($sourceGdImage === false) {
			return false;
		}
		$imageAttributes = self::getImageSize($sourceImagePath);

		$sourceAspectRatio = $imageAttributes['width'] / $imageAttributes['height'];
		$thumbnailAspectRatio = $thumbnailImageWidth / $thumbnailImageHeight;
		if ($imageAttributes['width'] <= $thumbnailImageWidth && $imageAttributes['height'] <= $thumbnailImageHeight) {
			$thumbnailImageWidth = $imageAttributes['width'];
			$thumbnailImageHeight = $imageAttributes['height'];
		} elseif ($thumbnailAspectRatio > $sourceAspectRatio) {
			$thumbnailImageWidth = (int) ($thumbnailImageHeight * $sourceAspectRatio);
		} else {
			$thumbnailImageHeight = (int) ($thumbnailImageWidth / $sourceAspectRatio);
		}
		$thumbnailGdImage = imagecreatetruecolor($thumbnailImageWidth, $thumbnailImageHeight);
		imagealphablending($thumbnailGdImage, false);
		imagesavealpha($thumbnailGdImage,true);
		$col = imagecolorallocatealpha($thumbnailGdImage, 255, 255, 255, 127);
		imagecopyresampled($thumbnailGdImage, $sourceGdImage, 0, 0, 0, 0, $thumbnailImageWidth, $thumbnailImageHeight, $imageAttributes['width'], $imageAttributes['height']);
		imagepng($thumbnailGdImage, $thumbnailImagePath, 7);
		imagedestroy($sourceGdImage);
		imagedestroy($thumbnailGdImage);

		return true;
	}
}