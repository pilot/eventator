<?php

namespace Event\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Event\EventBundle\Entity\Translation\Translation;

/**
 * Sponsor
 *
 * @ORM\Table(name="ev_sponsor")
 * @ORM\Entity
 */
class Sponsor
{
    use Translation;
    use EventTrait;

    const
        TYPE_PLATINUM = 1,
        TYPE_GOLD = 2,
        TYPE_SILVER = 3,
        TYPE_GENERAL = 4,
        TYPE_INFO = 5
    ;

    public static $types = [
        self::TYPE_PLATINUM => 'Platinum',
        self::TYPE_GOLD => 'Gold',
        self::TYPE_SILVER => 'Silver',
        self::TYPE_GENERAL => 'General',
        self::TYPE_INFO => 'Info'
    ];

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
     * @Assert\NotNull()
     * @ORM\Column(name="company", type="string", length=255)
     */
    private $company;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @var string
     *
     * @ORM\Column(name="homepage", type="string", length=255, nullable=true)
     */
    private $homepage;

    /**
     * @var integer
     *
     * @Assert\NotNull()
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive = true;

    /**
     * @var translations
     *
     * @ORM\OneToMany(targetEntity="\Event\EventBundle\Entity\Translation\SponsorTranslation", mappedBy="sponsor", cascade={"all"})
     */
    private $translations;

    /**
     * @var events
     *
     * @ORM\ManyToMany(targetEntity="Event", inversedBy="sponsors")
     */
    private $events;


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
     * Set company
     *
     * @param string $company
     * @return Sponsor
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
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

    /**
     * Set logo
     *
     * @param string $logo
     * @return Sponsor
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set homepage
     *
     * @param string $homepage
     * @return Speaker
     */
    public function setHomepage($homepage)
    {
        $this->homepage = $homepage;

        return $this;
    }

    /**
     * Get homepage
     *
     * @return string
     */
    public function getHomepage()
    {
        return $this->homepage;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Sponsor
     */
    public function setType($type)
    {
        if (!isset(self::$types[$type])) {
            throw new \InvalidArgumentException(sprintf('Sponsor type "%d" is not valid', $type));
        }

        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    public function getTypeName()
    {
        if (isset(self::$types[$this->getType()])) {
            return self::$types[$this->getType()];
        }

        return null;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Sponsor
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
}
