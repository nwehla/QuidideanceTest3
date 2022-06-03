<?php

namespace App\Entity;

use App\Repository\SurveyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SurveyRepository::class)
 */
class Survey
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="surveys")
     */
    private $categorie;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datecreation;

    /**
     * @ORM\OneToMany(targetEntity=Sondage::class, mappedBy="survey")
     */
    private $sondage;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datemiseajour;
    

    public function __construct()
    {
        $this->sondage = new ArrayCollection();
    }

   
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCategorie(): ?Categories
    {
        return $this->categorie;
    }

    public function setCategorie(?Categories $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getDatecreation(): ?\DateTimeInterface
    {
        return $this->datecreation;
    }

    public function setDatecreation(\DateTimeInterface $datecreation): self
    {
        $this->datecreation = $datecreation;

        return $this;
    }

    /**
     * @return Collection<int, Sondage>
     */
    public function getSondage(): Collection
    {
        return $this->sondage;
    }

    public function addSondage(Sondage $sondage): self
    {
        if (!$this->sondage->contains($sondage)) {
            $this->sondage[] = $sondage;
            $sondage->setSurvey($this);
        }

        return $this;
    }

    public function removeSondage(Sondage $sondage): self
    {
        if ($this->sondage->removeElement($sondage)) {
            // set the owning side to null (unless already changed)
            if ($sondage->getSurvey() === $this) {
                $sondage->setSurvey(null);
            }
        }

        return $this;
    }

    public function getDatemiseajour(): ?\DateTimeInterface
    {
        return $this->datemiseajour;
    }

    public function setDatemiseajour(?\DateTimeInterface $datemiseajour): self
    {
        $this->datemiseajour = $datemiseajour;

        return $this;
    }    
}
