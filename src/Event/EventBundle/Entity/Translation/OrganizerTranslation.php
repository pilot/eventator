<?php

namespace Event\EventBundle\Entity\Translation;

use Doctrine\ORM\Mapping as ORM;
use Event\EventBundle\Entity\Organizer;

/**
 * OrganizerTranslation
 *
 * @ORM\Table(name="ev_organizer_translation")
 * @ORM\Entity
 */
class OrganizerTranslation
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
     * @var Organizer
     *
     * @ORM\ManyToOne(targetEntity="\Event\EventBundle\Entity\Organizer", inversedBy="translations")
     */
    private $organizer;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;


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
     * Set organizer
     *
     * @param Organizer $organizer
     * @return Organizer
     */
    public function setOrganizer(Organizer $organizer)
    {
        $this->organizer = $organizer;

        return $this;
    }

    /**
     * Get organizer
     *
     * @return Organizer
     */
    public function getOrganizer()
    {
        return $this->organizer;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Sponsor
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
     * @return Sponsor
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
}
