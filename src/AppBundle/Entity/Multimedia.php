<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Multimedia
 *
 * @ORM\Table(name="multimedia")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MultimediaRepository")
 * @Vich\Uploadable
 */
class Multimedia extends Timestampable
{
    const YOUTUBE_URL = 'https://www.youtube.com/embed/';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="url_video", type="string", length=255, nullable=true)
     */
    private $urlVideo;

    /**
     * @var string
     *
     * @ORM\Column(name="file", type="string", length=255, nullable=true)
     */
    private $file;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="files", fileNameProperty="file")
     */
    private $fileVich;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="images", fileNameProperty="image")
     */
    private $imageVich;

    /**
     * @ORM\ManyToOne(targetEntity="MultimediaCategory", inversedBy="multimedias", cascade={"persist"})
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $category;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Multimedia
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set urlVideo
     *
     * @param string $urlVideo
     * @return Multimedia
     */
    public function setUrlVideo($urlVideo)
    {
        $this->urlVideo = $urlVideo;

        return $this;
    }

    /**
     * Get urlVideo
     *
     * @return string 
     */
    public function getUrlVideo()
    {
        return $this->urlVideo;
    }

    /**
     * Set file
     *
     * @param string $file
     * @return Multimedia
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string 
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set fileVich
     *
     * @param File|null $fileVich
     */
    public function setFileVich(File $fileVich = null)
    {
        $this->fileVich = $fileVich;

        if ($fileVich) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * Get fileVich
     *
     * @return File
     */
    public function getFileVich()
    {
        return $this->fileVich;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Multimedia
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set imageVich
     *
     * @param File|null $imageVich
     */
    public function setImageVich(File $imageVich = null)
    {
        $this->imageVich = $imageVich;

        if ($imageVich) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * Get imageVich
     *
     * @return File
     */
    public function getImageVich()
    {
        return $this->imageVich;
    }

    /**
     * @return MultimediaCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param MultimediaCategory $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        if(empty($this->getFileVich()) && empty($this->getUrlVideo())){
            $context->buildViolation('multimedia.url_or_file')
                ->atPath('urlVideo')
                ->addViolation();
        }

        if(!empty($this->getUrlVideo())){
            if(strpos($this->getUrlVideo(), self::YOUTUBE_URL) === false){
                $context->buildViolation('multimedia.url')
                    ->atPath('urlVideo')
                    ->addViolation();
            }
        }

        if(!empty($this->getFileVich())){
            if($this->getFileVich()->guessExtension() !== 'pdf'){
                $context->buildViolation('multimedia.file')
                    ->atPath('fileVich')
                    ->addViolation();
            }
        }
    }

    public function __toString()
    {
        return $this->title;
    }
}
