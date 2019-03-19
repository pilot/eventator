<?php
/**
 * Created by PhpStorm.
 * User: angeluss
 * Date: 14.08.18
 * Time: 18:09
 */

namespace Event\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Event\EventBundle\Entity\Translation\Translation;

/**
 * SoldWorkshop
 *
 * @ORM\Table(name="ev_wsh_schedule")
 * @ORM\Entity
 */

class WshSchedule
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
     * @ORM\Column(name="text", type="string", length=255, nullable=true)
     */
    private $text;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive = true;

    /**
     * @var Workshop
     *
     * @Assert\NotNull()
     * @ORM\ManyToOne(targetEntity="Workshop", inversedBy="schedule")
     */
    private $workshop;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer", length=255)
     */
    private $position;

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
     * Set text
     *
     * @param string $text
     * @return WshSchedule
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set workshop
     *
     * @param \Event\EventBundle\Entity\Workshop $workshop
     *
     * @return WshSchedule
     */
    public function setWorkshop(Workshop $workshop)
    {
        $this->workshop = $workshop;

        return $this;
    }

    /**
     * Get workshop
     *
     * @return \Event\EventBundle\Entity\Workshop
     */
    public function getWorkshop()
    {
        return $this->workshop;
    }
    
    /**
     * Set position
     *
     * @param integer $position
     * @return WshSchedule
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
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

}
