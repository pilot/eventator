<?php

namespace Event\EventBundle\Entity\Translation;

use Doctrine\ORM\Mapping as ORM;
use Event\EventBundle\Entity\Sponsor;

/**
 * SponsorTranslation
 *
 * @ORM\Table(name="ev_sponsor_translation")
 * @ORM\Entity
 */
class SponsorTranslation
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
     * @var Sponsor
     *
     * @ORM\ManyToOne(targetEntity="\Event\EventBundle\Entity\Sponsor", inversedBy="translations")
     */
    private $sponsor;

    /**
     * @var string
     *
     * @ORM\Column(name="company", type="string", length=255, nullable=true)
     */
    private $company;

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
     * Set sponsor
     *
     * @param Sponsor $sponsor
     * @return Sponsor
     */
    public function setSponsor(Sponsor $sponsor)
    {
        $this->sponsor = $sponsor;

        return $this;
    }

    /**
     * Get sponsor
     *
     * @return Sponsor
     */
    public function getSponsor()
    {
        return $this->sponsor;
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
}
