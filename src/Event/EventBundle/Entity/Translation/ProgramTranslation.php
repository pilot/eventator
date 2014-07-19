<?php

namespace Event\EventBundle\Entity\Translation;

use Doctrine\ORM\Mapping as ORM;
use Event\EventBundle\Entity\Program;

/**
 * ProgramTranslation
 *
 * @ORM\Table(name="ev_program_translation")
 * @ORM\Entity
 */
class ProgramTranslation
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
     * @var Program
     *
     * @ORM\ManyToOne(targetEntity="\Event\EventBundle\Entity\Program", inversedBy="translations")
     */
    private $program;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;


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
     * Set program
     *
     * @param Program $program
     * @return ProgramTranslation
     */
    public function setProgram(Program $program)
    {
        $this->program = $program;

        return $this;
    }

    /**
     * Get program
     *
     * @return Program
     */
    public function getProgram()
    {
        return $this->program;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Program
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
}
