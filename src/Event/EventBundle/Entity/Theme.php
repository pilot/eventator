<?php

namespace Event\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ArrayCollection;
use Event\EventBundle\Entity\Translation\Translation;

/**
 * Theme
 *
 * @ORM\Table(name="ev_theme")
 * @ORM\Entity(repositoryClass="Event\EventBundle\Entity\Repository\ThemeRepository")
 */
class Theme
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
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive = true;

    /**
     * @var string
     */
    private $file;
    
    private $changedFile;

    public function __construct()
    {
        $this->file = $this->title . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'style.css';
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
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Theme
     */
    public function setTitle($title)
    {
        $this->title = $title;

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
     * Set file
     *
     * @param string $file
     * @return Theme
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Theme
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set changedFile
     *
     * @param boolean $status
     * @return Theme
     */
    public function setChangedFile($status)
    {
        $this->changedFile = $status;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getChangedFile()
    {
        return $this->changedFile;
    }

    public function __toString()
    {
        return $this->title;
    }
}
