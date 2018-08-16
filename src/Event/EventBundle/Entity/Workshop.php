<?php

namespace Event\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Speaker
 *
 * @ORM\Table(name="ev_workshop")
 * @ORM\Entity
 */
class Workshop
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
     * @ORM\Column(name="time", type="string", length=255, nullable=true)
     */
    private $time;

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
     * @ORM\OneToMany(targetEntity="\Event\EventBundle\Entity\SoldWorkshop", mappedBy="workshop", cascade={"all"})
     */
    private $soldWorkshops;

    /**
     * @var Event
     *
     * @Assert\NotNull()
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="workshops")
     */
    private $event;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime")
     */
    private $dateCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_date", type="datetime", nullable=true)
     */
    private $dateUpdated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="\Event\EventBundle\Entity\WshSchedule", mappedBy="workshop", cascade={"all"})
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $schedule;

    const UAH = 1;
    const USD = 2;
    const EUR = 3;
    const RUR = 4;

    public function __construct()
    {
        $this->soldWorkshops = new ArrayCollection();
        $this->schedule = new ArrayCollection();
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
     * @return Workshop
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
     * @return Workshop
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
     * @return Workshop
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * Get time
     * 
     * @return string
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set time
     * 
     * @param string $time
     * @return Workshop
     */
    public function setTime($time)
    {
        $this->time = $time;
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
     * @return Workshop
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
     * @return Workshop
     */
    public function setCount($count)
    {
        $this->count = $count;
        return $this;
    }

    /**
     * Get sold workshops
     *
     * @return ArrayCollection
     */
    public function getSoldWorkshops()
    {
        return $this->soldWorkshops;
    }

    /**
     * Add sold workshops
     *
     * @param SoldWorkshop $soldWorkshop
     */
    public function addSoldWorkshop(SoldWorkshop $soldWorkshop)
    {
        $this->soldWorkshops[] = $soldWorkshop;

        $soldWorkshop->setWorkshop($this);
    }

    /**
     * Remove sold workshop
     *
     * @param SoldWorkshop $soldWorkshop
     */
    public function removeSoldWorkshop(SoldWorkshop $soldWorkshop)
    {
        $this->soldWorkshops->removeElement($soldWorkshop);
    }

    /**
     * Set event
     *
     * @param \Event\EventBundle\Entity\Event $event
     *
     * @return Workshop
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
        $sold = 0;
        foreach ($this->soldWorkshops as $soldWorkshop) {
            if($soldWorkshop->getStatus() == SoldWorkshop::STATUS_SOLD){
                $sold++;
            }
        }
        $remainingCount = $this->count - $sold;
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
     * @param $sign
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

    /**
     * Set date created
     *
     * @param \DateTime $createdDate
     * @return Workshop
     */
    public function setDateCreated($createdDate)
    {
        if (!$createdDate instanceOf \DateTime) {
            $createdDate = \DateTime::createFromFormat('m/d/Y', $createdDate);
        }
        $this->dateCreated = $createdDate;
        return $this;
    }
    
    /**
     * Get date created
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }
    
    /**
     * Set date updated
     *
     * @param \DateTime $updatedDate
     * @return Workshop
     */
    public function setDateUpdated($updatedDate)
    {
        if (!$updatedDate instanceOf \DateTime) {
            $updatedDate = \DateTime::createFromFormat('m/d/Y', $updatedDate);
        }
        $this->dateUpdated = $updatedDate;
        return $this;
    }
    /**
     * Get date updated
     *
     * @return \DateTime
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }
    
    /**
     * Set date 
     *
     * @param \DateTime $date
     * @return Workshop
     */
    public function setDate($date)
    {
        if (!$date instanceOf \DateTime) {
            $date = \DateTime::createFromFormat('m/d/Y', $date);
        }
        $this->date = $date;
        return $this;
    }
    
    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }


    /**
     * Get schedule
     *
     * @return ArrayCollection
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

    /**
     * Add schedule
     *
     * @param WshSchedule $schedule
     */
    public function addSchedule(WshSchedule $schedule)
    {
        $this->schedule[] = $schedule;

        $schedule->setWorkshop($this);
    }

    /**
     * Remove schedule
     *
     * @param WshSchedule $schedule
     */
    public function removeSchedule(WshSchedule $schedule)
    {
        $this->schedule->removeElement($schedule);
    }
}
