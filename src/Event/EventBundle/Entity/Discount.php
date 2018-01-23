<?php

namespace Event\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Event\EventBundle\Entity\Translation\Translation;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Speaker
 *
 * @ORM\Table(
 *      name="ev_discount",
 *      uniqueConstraints={@ORM\UniqueConstraint(columns={"name"})}
 * ) * @ORM\Entity
 */
class Discount
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
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="string", length=255, nullable=true)
     */
    private $amount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_to", type="datetime", nullable=true)
     */
    private $dateTo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive = true;

    /**
     * @var integer
     *
     * @ORM\Column(name="discount", type="string", length=255)
     */
    private $discount;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="SoldTicket", mappedBy="discount")
     */
    private $soldTickets;

    const TYPE_AMOUNT = 1;
    const TYPE_INFINITY = 2;
    const TYPE_DATE = 3;

    public static function getTypeLabel($type = null){
        $types = array(
            self::TYPE_AMOUNT => 'fixed amount',
            self::TYPE_INFINITY => 'infinity',
            self::TYPE_DATE => 'to date',
        );
        if(is_null($type)){
            return $types;
        } elseif(isset($types[$type])) {
            return $types[$type];
        }
        return $type;
    }

    public function getTypeString(){
        return self::getTypeLabel($this->type);
    }

    public function getCondition(){
        switch($this->type){
            case self::TYPE_AMOUNT:
                $amount = $this->amount - count($this->soldTickets);
                return $amount . ' times more';
            case self::TYPE_INFINITY:
                return '&infin;';
            case self::TYPE_DATE:
                return 'to ' . $this->dateTo->format('d M Y');
            default:
                return 'error';
        }
    }

    public function __construct()
    {
        $this->soldTickets = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Discount
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
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
     * Set type
     *
     * @param integer $type
     * @return Discount
     */
    public function setType($type)
    {
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

    /**
     * Set amount
     *
     * @param integer $amount
     * @return Discount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * Get amount
     *
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set discount
     *
     * @param integer $discount
     * @return Discount
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
        return $this;
    }

    /**
     * Get discount
     *
     * @return integer
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set dateTo
     *
     * @param \DateTime $dateTo
     * @return Discount
     */
    public function setDateTo($dateTo)
     {
         if (!$dateTo instanceOf \DateTime) {
             $dateTo = \DateTime::createFromFormat('m/d/Y', $dateTo);
         }

        $this->dateTo = $dateTo;

        return $this;
     }

     /**
      * Get dateTo
      *
      * @return \DateTime
      */
     public function getDateTo()
     {
            return $this->dateTo;
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
     * @return Discount
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }


    /**
     * Get sold tickets
     *
     * @return \Doctrine\Common\Collections\Collection
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

        $soldTicket->setDiscount($this);
    }

    /**
     * Remove soldTicket
     *
     * @param SoldTicket $soldTicket
     */
    public function removeSoldTicket(SoldTicket $soldTicket)
    {
        $this->soldTickets->removeElement($soldTicket);
    }

    public function isEnable(){
        if (false == $this->isActive){
            return false;
        }
        if($this->type == self::TYPE_AMOUNT && $this->amount == 0){
            return false;
        }
        if($this->type == self::TYPE_DATE){
            $now = new \DateTime();
            if($this->dateTo < $now) {
                return false;
            }
        }
        return true;
    }
}
