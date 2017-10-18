<?php

namespace Event\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ArrayCollection;
use Event\EventBundle\Entity\Translation\Translation;

/**
 * Media
 *
 * @ORM\Table(name="ev_media")
 * @ORM\Entity(repositoryClass="Event\EventBundle\Entity\Repository\MediaRepository")
 */
class Media
{

    /**
     * @var integer
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
     * @ORM\Column(name="filename", type="string", length=255)
     */
    private $filename;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="copyrightInfo", type="text", nullable=true)
     */
    private $copyrightInfo;

    /**
     * @var string
     *
     * @ORM\Column(name="mediaCredits", type="string", nullable=true)
     */
    private $mediaCredits;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime")
     */
    private $createdDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_date", type="datetime")
     */
    private $updatedDate;

    public function __construct()
    {
        if (is_null($this->getId())){
            $this->setCreatedDate(new \DateTime());
        }
        $this->setUpdatedDate(new \DateTime());
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
     * @return Media
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
     * Set filename
     *
     * @param string $filename
     * @return Media
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Media
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set copyrightInfo
     *
     * @param string $copyrightInfo
     * @return Media
     */
    public function setCopyrightInfo($copyrightInfo)
    {
        $this->copyrightInfo = $copyrightInfo;

        return $this;
    }

    /**
     * Get copyrightInfo
     *
     * @return string
     */
    public function getCopyrightInfo()
    {
        return $this->copyrightInfo;
    }

    /**
     * Set mediaCredits
     *
     * @param string $mediaCredits
     * @return Media
     */
    public function setMediaCredits($mediaCredits)
    {
        $this->mediaCredits = $mediaCredits;

        return $this;
    }

    /**
     * Get mediaCredits
     *
     * @return string
     */
    public function getMediaCredits()
    {
        return $this->mediaCredits;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     * @return Media
     */
    public function setCreatedDate($createdDate)
    {
        if (!$createdDate instanceOf \DateTime) {
            $createdDate = \DateTime::createFromFormat('m/d/Y', $createdDate);
        }

        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set updatedDate
     *
     * @param \DateTime $updatedDate
     * @return Media
     */
    public function setUpdatedDate($updatedDate)
    {
        if (!$updatedDate instanceOf \DateTime) {
            $updatedDate = \DateTime::createFromFormat('m/d/Y', $updatedDate);
        }

        $this->updatedDate = $updatedDate;

        return $this;
    }

    /**
     * Get updatedDate
     *
     * @return \DateTime
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }

    public function __toString()
    {
        return $this->title;
    }
}
