<?php

namespace AppBundle\Entity;

use AncaRebeca\FullCalendarBundle\Model\FullCalendarEvent;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Evenement
 *
 * @ORM\Table(name="evenement")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EvenementRepository")
 */
class Evenement
{



    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="localisation", type="string", length=255, nullable=false)
     */
    private $localisation;

    /**
     * @var string
     *
     * @ORM\Column(name="etablissement", type="string", length=255, nullable=false)
     */
    private $etablissement;
    /**
     * @var string
     *
     * @ORM\Column(name="categories", type="string", length=255, nullable=false)
     */
    private $categories;

    /**
     * @var \DateTime
     * @Assert\Type("DateTime")
     * @ORM\Column(name="datedebut", type="date")
     */
    public $dateDebut;
    /**
     * @var \DateTime
     * @Assert\Type("DateTime")
     *  @ORM\Column(name="datefin", type="date")
     * @Assert\Expression("value >= this.dateDebut",message="la date de fin doit être postérieure à la date de début")
     */
    private $dateFin;



    /**
     * @var integer
     *
     * @ORM\Column(name="nombreParticipants", type="integer", nullable=true)
     */
    private $nombreparticipants;

    /**
     * @var string
     *
     * @ORM\Column(name="imagepath", type="string", length=255, nullable=false)
     */
    private $imagepath;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getLocalisation()
    {
        return $this->localisation;
    }

    /**
     * @param string $localisation
     */
    public function setLocalisation($localisation)
    {
        $this->localisation = $localisation;
    }

    /**
     * @return string
     */
    public function getEtablissement()
    {
        return $this->etablissement;
    }

    /**
     * @param string $etablissement
     */
    public function setEtablissement($etablissement)
    {
        $this->etablissement = $etablissement;
    }

    /**
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * @param \DateTime $dateDebut
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;
    }

    /**
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * @param \DateTime $dateFin
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;
    }



    /**
     * @return int
     */
    public function getNombreparticipants()
    {
        return $this->nombreparticipants;
    }

    /**
     * @param int $nombreparticipants
     */
    public function setNombreparticipants($nombreparticipants)
    {
        $this->nombreparticipants = $nombreparticipants;
    }

    /**
     * @return string
     */
    public function getImagepath()
    {
        return $this->imagepath;
    }

    /**
     * @param string $imagepath
     */
    public function setImagepath($imagepath)
    {
        $this->imagepath = $imagepath;
    }




    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param string $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }


    /**
     * @return array
     */
    public function toArray()
    {
        // TODO: Implement toArray() method.
    }
}

