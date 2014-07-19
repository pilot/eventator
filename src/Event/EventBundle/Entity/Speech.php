<?php

namespace Event\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Event\EventBundle\Entity\Translation\Translation;

/**
 * Speech
 *
 * @ORM\Table(name="ev_speech")
 * @ORM\Entity
 */
class Speech
{
    use Translation;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Speaker
     *
     * @Assert\NotNull()
     * @ORM\ManyToOne(targetEntity="Speaker", inversedBy="speeches")
     */
    private $speaker;

    /**
     * @var string
     *
     * @Assert\NotNull()
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @Assert\NotNull()
     * @ORM\Column(name="language", type="string", length=5)
     */
    private $language = 'en';

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="slide", type="text", nullable=true)
     */
    private $slide;

    /**
     * @var string
     *
     * @ORM\Column(name="video", type="text", nullable=true)
     */
    private $video;

    /**
     * @var translations
     *
     * @ORM\OneToMany(targetEntity="\Event\EventBundle\Entity\Translation\SpeechTranslation", mappedBy="speech", cascade={"all"})
     */
    private $translations;

    /**
     * @var string
     *
     * @ORM\OneToOne(targetEntity="Program", mappedBy="speech", cascade={"remove"})
     */
    private $program;


    public function __construct()
    {
        $this->translations = new ArrayCollection();
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
     * Set speaker
     *
     * @param Speaker $speaker
     * @return Speech
     */
    public function setSpeaker(Speaker $speaker)
    {
        $this->speaker = $speaker;

        return $this;
    }

    /**
     * Get speaker
     *
     * @return Speaker
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
     * Set language
     *
     * @param string $language
     * @return Speech
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
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
