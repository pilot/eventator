<?php

namespace Event\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ArrayCollection;
use Event\EventBundle\Entity\Translation\Translation;

/**
 * Event
 *
 * @ORM\Table(name="ev_event")
 * @ORM\Entity(repositoryClass="Event\EventBundle\Entity\Repository\EventRepository")
 */
class Event
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
     * @ORM\Column(name="host", type="string", length=255)
     */
    private $host;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="brief_description", type="text", nullable=true)
     */
    private $briefDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="about_description", type="text", nullable=true)
     */
    private $aboutDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="sponsor_description", type="text", nullable=true)
     */
    private $sponsorDescription;

    /**
     * Document for sponsor with benefits from event sponsoring
     * @var string
     *
     * @ORM\Column(name="sponsor_guide", type="string", nullable=true)
     */
    private $sponsorGuide;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255, nullable=true)
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime")
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="datetime")
     */
    private $endDate;

    /**
     * @var string
     *
     * @ORM\Column(name="venue", type="string", length=255)
     */
    private $venue;

    /**
     * @var string
     *
     * @ORM\Column(name="venue_address", type="text", nullable=true)
     */
    private $venueAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="string", length=50, nullable=true)
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="string", length=50, nullable=true)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", nullable=true)
     */
    private $logo;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter", type="string", length=255, nullable=true)
     */
    private $twitter;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook", type="string", length=255, nullable=true)
     */
    private $facebook;

    /**
     * @var string
     *
     * @ORM\Column(name="google", type="string", length=255, nullable=true)
     */
    private $google;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * Just hold all additional contact info which want to be shown on contact page
     * @var string
     *
     * @ORM\Column(name="contact", type="text", nullable=true)
     */
    private $contact;

    /**
     * Embed code for foreign ticketing systems like eventbrite.com, ticketforevent.com
     * @var string
     *
     * @ORM\Column(name="embed_ticket", type="text", nullable=true)
     */
    private $embedTicket;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive = true;

    /**
     * @var string
     *
     * @ORM\Column(name="slide_one", type="string", length=255, nullable=true)
     */
    private $slideOne;

     /**
     * @var string
     *
     * @ORM\Column(name="slide_two", type="string", length=255, nullable=true)
     */
    private $slideTwo;

     /**
     * @var string
     *
     * @ORM\Column(name="slide_three", type="string", length=255, nullable=true)
     */
    private $slideThree;

    /**
     * @var translations
     *
     * @ORM\OneToMany(targetEntity="\Event\EventBundle\Entity\Translation\EventTranslation", mappedBy="event", cascade={"all"})
     */
    private $translations;

    /**
     * @var speakers
     *
     * @ORM\ManyToMany(targetEntity="Speaker", mappedBy="events")
     */
    private $speakers;

    /**
     * @var Speech
     *
     * @ORM\OneToMany(targetEntity="Speech", mappedBy="event", cascade={"all"})
     */
    private $speeches;

    /**
     * @var sponsors
     *
     * @ORM\ManyToMany(targetEntity="Sponsor", mappedBy="events")
     */
    private $sponsors;

    /**
     * @var program
     *
     * @ORM\ManyToMany(targetEntity="Program", mappedBy="events")
     */
    private $program;

    /**
     * @var ogranizers
     *
     * @ORM\ManyToMany(targetEntity="Organizer", mappedBy="events")
     */
    private $organizers;

    /**
     * @var CallForPaper
     *
     * @ORM\OneToMany(targetEntity="CallForPaper", mappedBy="event", cascade={"all"})
     */
    private $papers;

    /**
     * @var Ticket
     *
     * @ORM\OneToMany(targetEntity="Ticket", mappedBy="event", cascade={"all"})
     * @ORM\OrderBy({"price" = "ASC"})
     */
    private $tickets;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_title", type="string", length=255, nullable=true)
     */
    private $metaTitle;
    /**
     * @var string
     *
     * @ORM\Column(name="meta_desc", type="text", nullable=true)
     */
    private $metaDesc;
    /**
     * @var string
     *
     * @ORM\Column(name="meta_kw", type="string", length=255, nullable=true)
     */
    private $metaKw;
    /**
     * @var string
     *
     * @ORM\Column(name="og_title", type="string", length=255, nullable=true)
     */
    private $ogTitle;
    /**
     * @var string
     *
     * @ORM\Column(name="og_desc", type="text", nullable=true)
     */
    private $ogDesc;
    /**
     * @var string
     *
     * @ORM\Column(name="og_url", type="string", length=255, nullable=true)
     */
    private $ogUrl;
    /**
     * @var string
     *
     * @ORM\Column(name="og_image", type="string", length=255, nullable=true)
     */
    private $ogImage;


    public function __construct()
    {
        $this->translations = new ArrayCollection();
        $this->speakers = new ArrayCollection();
        $this->speeches = new ArrayCollection();
        $this->sponsors = new ArrayCollection();
        $this->program = new ArrayCollection();
        $this->organizers = new ArrayCollection();
        $this->papers = new ArrayCollection();
        $this->tickets = new ArrayCollection();
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
     * Set host
     *
     * @param string $host
     * @return Event
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Get host
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Event
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
     * Set briefDescription
     *
     * @param string $briefDescription
     * @return Event
     */
    public function setBriefDescription($briefDescription)
    {
        $this->briefDescription = $briefDescription;

        return $this;
    }

    /**
     * Get briefDescription
     *
     * @return string
     */
    public function getBriefDescription()
    {
        return $this->briefDescription;
    }

    /**
     * Set aboutDescription
     *
     * @param string $aboutDescription
     * @return Event
     */
    public function setAboutDescription($aboutDescription)
    {
        $this->aboutDescription = $aboutDescription;

        return $this;
    }

    /**
     * Get aboutDescription
     *
     * @return string
     */
    public function getAboutDescription()
    {
        return $this->aboutDescription;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Event
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Set sponsorDescription
     *
     * @param string $sponsorDescription
     * @return Event
     */
    public function setSponsorDescription($sponsorDescription)
    {
        $this->sponsorDescription = $sponsorDescription;

        return $this;
    }

    /**
     * Get sponsorDescription
     *
     * @return string
     */
    public function getSponsorDescription()
    {
        return $this->sponsorDescription;
    }

    /**
     * Set sponsorGuide
     *
     * @param string $sponsorGuide
     * @return Event
     */
    public function setSponsorGuide($sponsorGuide)
    {
        $this->sponsorGuide = $sponsorGuide;

        return $this;
    }

    /**
     * Get sponsorGuide
     *
     * @return string
     */
    public function getSponsorGuide()
    {
        return $this->sponsorGuide;
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
     * Set country
     *
     * @param string $country
     * @return Event
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return Event
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Event
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Event
     */
    public function setStartDate($startDate)
    {
        if (!$startDate instanceOf \DateTime) {
            $startDate = \DateTime::createFromFormat('m/d/Y', $startDate);
        }

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
     * @return Event
     */
    public function setEndDate($endDate)
    {
        if (!$endDate instanceOf \DateTime) {
            $endDate = \DateTime::createFromFormat('m/d/Y', $endDate);
        }

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
     * Set venue
     *
     * @param string $venue
     * @return Event
     */
    public function setVenue($venue)
    {
        $this->venue = $venue;

        return $this;
    }

    /**
     * Get venue
     *
     * @return string
     */
    public function getVenue()
    {
        return $this->venue;
    }

    /**
     * Set venueAddress
     *
     * @param string $venueAddress
     * @return Event
     */
    public function setVenueAddress($venueAddress)
    {
        $this->venueAddress = $venueAddress;

        return $this;
    }

    /**
     * Get venueAddress
     *
     * @return string
     */
    public function getVenueAddress()
    {
        return $this->venueAddress;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     * @return Event
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return Event
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set logo
     *
     * @param string $logo
     * @return Event
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
     * Set twitter
     *
     * @param string $twitter
     * @return Event
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;

        return $this;
    }

    /**
     * Get twitter
     *
     * @return string
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Set facebook
     *
     * @param string $facebook
     * @return Event
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * Get facebook
     *
     * @return string
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * Set google
     *
     * @param string $google
     * @return Event
     */
    public function setGoogle($google)
    {
        $this->google = $google;

        return $this;
    }

    /**
     * Get google
     *
     * @return string
     */
    public function getGoogle()
    {
        return $this->google;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Event
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set contact
     *
     * @param string $contact
     * @return Event
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set embedTicket
     *
     * @param string $embedTicket
     * @return Event
     */
    public function setEmbedTicket($embedTicket)
    {
        $this->embedTicket = $embedTicket;

        return $this;
    }

    /**
     * Get embedTicket
     *
     * @return string
     */
    public function getEmbedTicket()
    {
        return $this->embedTicket;
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

    /**
     * Set slideOne
     *
     * @param string $slideOne
     * @return Event
     */
    public function setSlideOne($slide)
    {
        $this->slideOne = $slide;

        return $this;
    }

    /**
     * Get slideOne
     *
     * @return string
     */
    public function getSlideOne()
    {
        return $this->slideOne;
    }

    /**
     * Set slideTwo
     *
     * @param string $slideTwo
     * @return Event
     */
    public function setSlideTwo($slide)
    {
        $this->slideTwo = $slide;

        return $this;
    }

    /**
     * Get slideTwo
     *
     * @return string
     */
    public function getSlideTwo()
    {
        return $this->slideTwo;
    }

    /**
     * Set slideThree
     *
     * @param string $slideThree
     * @return Event
     */
    public function setSlideThree($slide)
    {
        $this->slideThree = $slide;

        return $this;
    }

    /**
     * Get slideThree
     *
     * @return string
     */
    public function getSlideThree()
    {
        return $this->slideThree;
    }

    /**
     * Get speakers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSpeakers()
    {
        return $this->speakers;
    }

    /**
     * Get speeches
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSpeeches()
    {
        return $this->speeches;
    }

    /**
     * Get sponsors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSponsors()
    {
        return $this->sponsors;
    }

    /**
     * Get program
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgram()
    {
        return $this->program;
    }

    /**
     * Get organizers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrganizers()
    {
        return $this->organizers;
    }

    public function getSpecifiedSponsors($type = Sponsor::TYPE_INFO)
    {
        $criteria = Criteria::create();
        $criteria->where(
            Criteria::expr()->eq('type', $type)
        );
        $criteria->andWhere(
            Criteria::expr()->eq('isActive', true)
        );

        return $this->getSponsors()->matching($criteria);
    }

    public function getSponsorsExclude($type = Sponsor::TYPE_INFO)
    {
        $criteria = Criteria::create();
        $criteria->where(
            Criteria::expr()->neq('type', $type)
        );
        $criteria->andWhere(
            Criteria::expr()->eq('isActive', true)
        );

        return $this->getSponsors()->matching($criteria);
    }

    public function __toString()
    {
        return $this->title;
    }

    /**
     * Get papers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPapers()
    {
        return $this->papers;
    }

    /**
     * Get tickets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    /**
     * Add ticket
     *
     * @param Ticket $ticket
     */
    public function addTicket(Ticket $ticket)
    {
        $this->tickets[] = $ticket;

        $ticket->setEvent($this);
    }

    /**
     * Remove ticket
     *
     * @param Ticket $ticket
     */
    public function removeTicket(Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * get metaTitle
     *
     * @return string
     */
    public function getMetaTitle(){
        return $this->metaTitle;
    }

    /**
     * set metaTitle
     *
     * @param $metaTitle
     * @return Event
     */
    public function setMetaTitle($metaTitle){
        $this->metaTitle = $metaTitle;
        return $this;
    }

    /**
     * get metaDesc
     *
     * @return string
     */
    public function getMetaDesc(){
        return $this->metaDesc;
    }

    /**
     * set metaDesc
     *
     * @param $metaDesc
     * @return Event
     */
    public function setMetaDesc($metaDesc){
        $this->metaDesc = $metaDesc;
        return $this;
    }

    /**
     * get metaKw
     *
     * @return string
     */
    public function getMetaKw(){
        return $this->metaKw;
    }

    /**
     * set metaKw
     *
     * @param $metaKw
     * @return Event
     */
    public function setMetaKw($metaKw){
        $this->metaKw = $metaKw;
        return $this;
    }

    /**
     * get ogTitle
     *
     * @return string
     */
    public function getOgTitle(){
        return $this->ogTitle;
    }

    /**
     * set ogTitle
     *
     * @param $ogTitle
     * @return Event
     */
    public function setOgTitle($ogTitle){
        $this->ogTitle = $ogTitle;
        return $this;
    }

    /**
     * get ogDesc
     *
     * @return string
     */
    public function getOgDesc(){
        return $this->ogDesc;
    }

    /**
     * set ogDesc
     *
     * @param $ogDesc
     * @return Event
     */
    public function setOgDesc($ogDesc){
        $this->ogDesc = $ogDesc;
        return $this;
    }

    /**
     * get ogUrl
     *
     * @return string
     */
    public function getOgUrl(){
        return $this->ogUrl;
    }

    /**
     * set ogUrl
     *
     * @param $ogUrl
     * @return Event
     */
    public function setOgUrl($ogUrl){
        $this->ogUrl = $ogUrl;
        return $this;
    }

    /**
     * get ogImage
     *
     * @return string
     */
    public function getOgImage(){
        return $this->ogImage;
    }

    /**
     * set ogImage
     *
     * @param $ogImage
     * @return Event
     */
    public function setOgImage($ogImage){
        $this->ogImage = $ogImage;
        return $this;
    }

    public function getLogicMetaTitle(){
        return $this->metaTitle ? $this->metaTitle : $this->title;
    }

    public function getLogicOgTitle(){
        return $this->ogTitle ? $this->ogTitle : $this->getLogicMetaTitle();
    }

    public function getLogicMetaDesc(){
        return $this->metaDesc;
    }

    public function getLogicOgDesc(){
        return $this->ogDesc ? $this->ogDesc : $this->getLogicDesc();
    }
}
