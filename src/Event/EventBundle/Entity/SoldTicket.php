<?php

namespace Event\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Event\EventBundle\Entity\Translation\Translation;

/**
 * SoldTicket
 *
 * @ORM\Table(name="ev_sold_ticket")
 * @ORM\Entity
 */
class SoldTicket
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
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @Assert\NotNull()
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="uid", type="string", length=255)
     */
    private $uid;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var Ticket
     *
     * @Assert\NotNull()
     * @ORM\ManyToOne(targetEntity="Ticket", inversedBy="soldTickets")
     */
    private $ticket;

    /**
     * @var Discount
     *
     * @ORM\ManyToOne(targetEntity="Discount", inversedBy="soldTickets")
     */
    private $discount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime")
     */
    private $dateCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sold_date", type="datetime", nullable=true)
     */
    private $dateSold;

    /**
     * @var integer
     *
     * @Assert\NotNull()
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    public $count;

    const STATUS_RESERVED = 1;
    const STATUS_SOLD = 2;

    const QR_GEN_URL = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=';

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
     * Set firstName
     *
     * @param string $firstName
     * @return Speaker
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return SoldTicket
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return SoldTicket
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

    public function getFullName()
    {
        $names = array_filter(
            array($this->firstName, $this->lastName),
            function ($element) {
                return (boolean) trim($element);
            }
        );

        return trim(implode(' ', $names));
    }


    /**
     * Set ticket
     *
     * @param \Event\EventBundle\Entity\Ticket $ticket
     *
     * @return SoldTicket
     */
    public function setTicket(Ticket $ticket)
    {
        $this->ticket = $ticket;

        return $this;
    }

    /**
     * Get ticket
     *
     * @return \Event\EventBundle\Entity\Ticket
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return SoldTicket
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Set discount
     *
     * @param \Event\EventBundle\Entity\Discount $discount
     *
     * @return SoldTicket
     */
    public function setDiscount(Discount $discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return \Event\EventBundle\Entity\Discount
     */
    public function getDiscount()
    {
        return $this->discount;
    }
    
    //@TODO
    public function getDiscountString(){
        return '-';
    }

    /**
     * Set uid
     *
     * @param string $uid
     * @return SoldTicket
     */
    public function setUid($uid)
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * Get uid
     *
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    public function getStatusLabel(){
        $labels = [
            self::STATUS_RESERVED => 'reserved',
            self::STATUS_SOLD => 'sold',
        ];
        return isset ($labels[$this->status]) ? $labels[$this->status] : $this->status;
    }

    public function getCodeForQR(){
        return md5($this->uid . '_' . $this->id);
    }

    public function getQRCode(){
        $code = $this->id . '***' . $this->getCodeForQR();
        return self::QR_GEN_URL . $code;
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
     * @return SoldTicket
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * Set date created
     *
     * @param \DateTime $createdDate
     * @return SoldTicket
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
     * Set date sold
     *
     * @param \DateTime $soldDate
     * @return SoldTicket
     */
    public function setDateSold($soldDate)
    {
        if (!$soldDate instanceOf \DateTime) {
            $soldDate = \DateTime::createFromFormat('m/d/Y', $soldDate);
        }
        $this->dateSold = $soldDate;
        return $this;
    }
    /**
     * Get date sold
     *
     * @return \DateTime
     */
    public function getDateSold()
    {
        return $this->dateSold;
    }
}
