<?php

namespace Event\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Event\EventBundle\Entity\Translation\Translation;

/**
 * Program
 *
 * @ORM\Table(name="ev_program")
 * @ORM\Entity
 */
class Program
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_topic", type="boolean")
     */
    private $isTopic = false;

    /**
     * Support link for instance if topic also used for workshop/training presentation
     * link will suite to redirect to the registration
     *
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=255, nullable=true)
     */
    private $link;

    /**
     * @var \DateTime
     *
     * @Assert\NotNull()
     * @ORM\Column(name="startDate", type="datetime")
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="datetime", nullable=true)
     */
    private $endDate;

    /**
     * @var translations
     *
     * @ORM\OneToMany(targetEntity="\Event\EventBundle\Entity\Translation\ProgramTranslation", mappedBy="program", cascade={"all"})
     */
    private $translations;

    /**
     * @var string
     *
     * @ORM\OneToOne(targetEntity="Speech", inversedBy="program")
     */
    private $speech;


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
     * Set title
     *
     * @param string $title
     * @return Program
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
     * Set link
     *
     * @param string $link
     * @return Program
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set isTopic
     *
     * @param boolean $isTopic
     * @return Program
     */
    public function setIsTopic($isTopic)
    {
        $this->isTopic = $isTopic;

        return $this;
    }

    /**
     * Get isTopic
     *
     * @return boolean
     */
    public function getIsTopic()
    {
        return $this->isTopic;
    }

    /**
     * Set speaker
     *
     * @param string $speaker
     * @return Program
     */
    public function setSpeaker($speaker)
    {
        $this->speaker = $speaker;

        return $this;
    }

    /**
     * Get speaker
     *
     * @return string
     */
    public function getSpeaker()
    {
        return $this->speaker;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Program
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return Program
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
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
     * Set speech
     *
     * @param Speech $speech
     */
    public function setSpeech(Speech $speech)
    {
        $this->speech = $speech;
    }
}
