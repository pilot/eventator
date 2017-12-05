<?php

namespace Event\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table(name="ev_show_blocks")
 * @ORM\Entity(repositoryClass="Event\EventBundle\Entity\Repository\ShowBlocksRepository")
 */
class ShowBlocks
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
     * @var boolean
     *
     * @ORM\Column(name="showWhereItBeSection", type="boolean", options={"default" : 1})
     */
    private $showWhereItBeSection = true;

    /**
     * @var boolean
     *
     * @ORM\Column(name="showSpeakersSection", type="boolean", options={"default" : 1})
     */
    private $showSpeakersSection = true;

    /**
     * @var boolean
     *
     * @ORM\Column(name="showScheduleSection", type="boolean", options={"default" : 1})
     */
    private $showScheduleSection = true;

    /**
     * @var boolean
     *
     * @ORM\Column(name="showAboutSection", type="boolean", options={"default" : 1})
     */
    private $showAboutSection = true;

    /**
     * @var boolean
     *
     * @ORM\Column(name="showVenueSection", type="boolean", options={"default" : 1})
     */
    private $showVenueSection = true;

    /**
     * @var boolean
     *
     * @ORM\Column(name="showMapSection", type="boolean", options={"default" : 1})
     */
    private $showMapSection = true;

    /**
     * @var boolean
     *
     * @ORM\Column(name="showHowItWasSection", type="boolean", options={"default" : 1})
     */
    private $showHowItWasSection = true;

    /**
     * @var boolean
     *
     * @ORM\Column(name="showSponsorsSection", type="boolean", options={"default" : 1})
     */
    private $showSponsorsSection = true;

    /**
     * @var boolean
     *
     * @ORM\Column(name="showOrganizersSection", type="boolean", options={"default" : 1})
     */
    private $showOrganizersSection = true;

    /**
     * @var boolean
     *
     * @ORM\Column(name="showContactSection", type="boolean", options={"default" : 1})
     */
    private $showContactSection = true;

    public function __construct()
    {

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
     * @return boolean
     */
    public function getShowWhereItBeSection()
    {
        return $this->showWhereItBeSection;
    }

    /**
     * @param boolean $showWhereItBeSection
     * @return ShowBlocks
     */
    public function setShowWhereItBeSection($showWhereItBeSection)
    {
        $this->showWhereItBeSection = $showWhereItBeSection;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getShowSpeakersSection()
    {
        return $this->showSpeakersSection;
    }

    /**
     * @param boolean $showSpeakersSection
     * @return ShowBlocks
     */
    public function setShowSpeakersSection($showSpeakersSection)
    {
        $this->showSpeakersSection = $showSpeakersSection;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getShowScheduleSection()
    {
        return $this->showScheduleSection;
    }

    /**
     * @param boolean $showScheduleSection
     * @return ShowBlocks
     */
    public function setShowScheduleSection($showScheduleSection)
    {
        $this->showScheduleSection = $showScheduleSection;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getShowAboutSection()
    {
        return $this->showAboutSection;
    }

    /**
     * @param boolean $showAboutSection
     * @return ShowBlocks
     */
    public function setShowAboutSection($showAboutSection)
    {
        $this->showAboutSection = $showAboutSection;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getShowVenueSection()
    {
        return $this->showVenueSection;
    }

    /**
     * @param boolean $showVenueSection
     * @return ShowBlocks
     */
    public function setShowVenueSection($showVenueSection)
    {
        $this->showVenueSection = $showVenueSection;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getShowMapSection()
    {
        return $this->showMapSection;
    }

    /**
     * @param boolean $showMapSection
     * @return ShowBlocks
     */
    public function setShowMapSection($showMapSection)
    {
        $this->showMapSection = $showMapSection;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getShowHowItWasSection()
    {
        return $this->showHowItWasSection;
    }

    /**
     * @param boolean $showHowItWasSection
     * @return ShowBlocks
     */
    public function setShowHowItWasSection($showHowItWasSection)
    {
        $this->showHowItWasSection = $showHowItWasSection;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getShowSponsorsSection()
    {
        return $this->showSponsorsSection;
    }

    /**
     * @param boolean $showSponsorsSection
     * @return ShowBlocks
     */
    public function setShowSponsorsSection($showSponsorsSection)
    {
        $this->showSponsorsSection = $showSponsorsSection;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getShowOrganizersSection()
    {
        return $this->showOrganizersSection;
    }

    /**
     * @param boolean $showOrganizersSection
     * @return ShowBlocks
     */
    public function setShowOrganizersSection($showOrganizersSection)
    {
        $this->showOrganizersSection = $showOrganizersSection;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getShowContactSection()
    {
        return $this->showContactSection;
    }

    /**
     * @param boolean $showContactSection
     * @return ShowBlocks
     */
    public function setShowContactSection($showContactSection)
    {
        $this->showContactSection = $showContactSection;

        return $this;
    }
    
}
