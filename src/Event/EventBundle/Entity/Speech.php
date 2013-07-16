<?php

namespace Event\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Speech
 *
 * @ORM\Table(name="ev_speech")
 * @ORM\Entity
 */
class Speech
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
     * @var integer
     *
     * @ORM\Column(name="speaker", type="integer")
     */
    private $speaker;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="slide", type="text")
     */
    private $slide;

    /**
     * @var string
     *
     * @ORM\Column(name="video", type="text")
     */
    private $video;


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
     * Set speaker
     *
     * @param integer $speaker
     * @return Speech
     */
    public function setSpeaker($speaker)
    {
        $this->speaker = $speaker;
    
        return $this;
    }

    /**
     * Get speaker
     *
     * @return integer 
     */
    public function getSpeaker()
    {
        return $this->speaker;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Speech
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
     * Set description
     *
     * @param string $description
     * @return Speech
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
     * Set slide
     *
     * @param string $slide
     * @return Speech
     */
    public function setSlide($slide)
    {
        $this->slide = $slide;
    
        return $this;
    }

    /**
     * Get slide
     *
     * @return string 
     */
    public function getSlide()
    {
        return $this->slide;
    }

    /**
     * Set video
     *
     * @param string $video
     * @return Speech
     */
    public function setVideo($video)
    {
        $this->video = $video;
    
        return $this;
    }

    /**
     * Get video
     *
     * @return string 
     */
    public function getVideo()
    {
        return $this->video;
    }
}
