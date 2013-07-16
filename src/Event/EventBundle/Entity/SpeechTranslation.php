<?php

namespace Event\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SpeechTranslation
 *
 * @ORM\Table(name="ev_speech_translation")
 * @ORM\Entity
 */
class SpeechTranslation
{
    use Locale;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Speech
     *
     * @ORM\ManyToOne(targetEntity="Speech", inversedBy="translations")
     */
    private $speech;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set speech
     *
     * @param Speech $speech
     * @return SpeechTranslation
     */
    public function setSpeech(Speech $speech)
    {
        $this->speech = $speech;

        return $this;
    }

    /**
     * Get speech
     *
     * @return Speech
     */
    public function getSpeech()
    {
        return $this->speech;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return SpeechTranslation
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
     * @return SpeechTranslation
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
     * @return SpeechTranslation
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
     * @return SpeechTranslation
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
