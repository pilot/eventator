<?php

namespace Event\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Speaker
 *
 * @ORM\Table(name="ev_ticket")
 * @ORM\Entity
 */
class Ticket
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
     * @Assert\NotNull()
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var integer
     *
     * @Assert\NotNull()
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    /**
     * @var integer
     *
     * @ORM\Column(name="currency", type="integer")
     */
    private $currency;

    /**
     * @var integer
     *
     * @ORM\Column(name="lunch_price", type="integer", nullable=true)
     */
    private $lunch_price;

    /**
     * @var integer
     * after party price
     *
     * @ORM\Column(name="ap_price", type="integer", nullable=true)
     */
    private $ap_price;


    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive = true;

    /**
     * @var integer
     *
     * @ORM\Column(name="count", type="integer")
     */
    private $count;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="\Event\EventBundle\Entity\SoldTicket", mappedBy="ticket", cascade={"all"})
     */
    private $soldTickets;

    /**
     * @var Event
     *
     * @Assert\NotNull()
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="tickets")
     */
    private $event;

    const UAH = 1;
    const USD = 2;
    const EUR = 3;
    const RUR = 4;

    public function __construct()
    {
        $this->soldTickets = new ArrayCollection();
    }

    /**
     * 
     * Get id
     * 
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get name
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     * 
     * @param string $name
     * @return Ticket
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get price
     * 
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set price
     * 
     * @param integer $price
     * @return Ticket
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * Get currency
     * 
     * @return integer
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set currency
     * 
     * @param integer $currency
     * @return Ticket
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * Get lunch price
     * if null, lunch is not enable
     * 
     * @return integer
     */
    public function getLunchPrice()
    {
        return $this->lunch_price;
    }

    /**
     * Set lunch price
     * 
     * @param integer $lunch_price
     * @return Ticket
     */
    public function setLunchPrice($lunch_price)
    {
        $this->lunch_price = $lunch_price;
        return $this;
    }

    /**
     * Get after party price
     * if null, after party is not enable
     * 
     * @return integer
     */
    public function getApPrice()
    {
        return $this->ap_price;
    }

    /**
     * Set after party price
     * 
     * @param integer $ap_price
     * @return Ticket
     */
    public function setApPrice($ap_price)
    {
        $this->ap_price = $ap_price;
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
     * Set isActive
     * 
     * @param boolean $isActive
     * @return Ticket
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * Get full count
     * 
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set full count
     * 
     * @param integer $count
     * @return Ticket
     */
    public function setCount($count)
    {
        $this->count = $count;
        return $this;
    }

    /**
     * Get sold tickets
     *
     * @return ArrayCollection
     */
    public function getSoldTickets()
    {
        return $this->soldTickets;
    }

    /**
     * Add sold ticket
     *
     * @param SoldTicket $soldTicket
     */
    public function addSoldTicket(SoldTicket $soldTicket)
    {
        $this->soldTickets[] = $soldTicket;

        $soldTicket->setTicket($this);
    }

    /**
     * Remove sold ticket
     *
     * @param SoldTicket $soldTicket
     */
    public function removeSoldTicket(SoldTicket $soldTicket)
    {
        $this->soldTickets->removeElement($soldTicket);
    }

    /**
     * Set event
     *
     * @param \Event\EventBundle\Entity\Event $event
     *
     * @return Ticket
     */
    public function setEvent(Event $event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return \Event\EventBundle\Entity\Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Get count of remaining tickets
     *
     * @return integer
     */
    public function getRemainingCount(){
        $remainingCount = $this->count - count($this->soldTickets);
        return $remainingCount;
    }

    /**
     * Get currency label based on $this->currency or labels array (f.e. for dropdowns)
     *
     * @param integer $label
     * @return array|mixed|null
     */
    public static function getCurrencyLabels($label = null)
    {
        $labels = array(
            self::UAH => 'UAH',
            self::USD => 'USD',
            self::EUR => 'EUR',
            self::RUR => 'RUB',
        );
        if(is_null($label)){
            return $labels;
        } else if(isset($labels[$label])){
            return $labels[$label];
        }
        return $label;
    }
    
    public function getCurrencyLabel($sign = false){
        if($sign) {
            return self::getCurrencySigns($this->currency);
        }
        return self::getCurrencyLabels($this->currency);
    }

    /**
     * get price with currency. F.e. for twig view
     * 
     * @return string
     */
    public function getPriceWithLabel($sign = false){
        if($sign) {
            return self::getCurrencySigns($this->currency) . $this->price;
        }
        return $this->price . ' ' . self::getCurrencyLabels($this->currency);
    }

    /**
     * get lunch price with currency. F.e. for twig view
     *
     * @return string
     */
    public function getLunch($sign = false){
        if(is_null($this->lunch_price)){
            return '-';
        }
        if($sign) {
            return self::getCurrencySigns($this->currency) . $this->lunch_price;
        }
        return $this->lunch_price . ' ' . self::getCurrencyLabels($this->currency);
    }

    /**
     * get after party price with currency. F.e. for twig view
     *
     * @return string
     */
    public function getAP($sign = false){
        if(is_null($this->ap_price)){
            return '-';
        }
        if($sign) {
            return self::getCurrencySigns($this->currency) . $this->ap_price;
        }
        return $this->ap_price . ' ' . self::getCurrencyLabels($this->currency);
    }

    /**
     * Get currency typo sign based on $this->currency or signs array (f.e. for dropdowns)
     *
     * @param integer $sign
     * @return array|mixed|null
     */
    public static function getCurrencySigns($sign = null)
    {
        $signs = array(
            self::UAH => '₴',
            self::USD => '$',
            self::EUR => '€',
            self::RUR => '₽',
        );

        return is_null($sign) ? $signs : isset($signs[$sign]) ? $signs[$sign] : $sign;
    }

}
