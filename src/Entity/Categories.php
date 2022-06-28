<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoriesRepository::class)
 */
class Categories
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
     * @ORM\ManyToMany(targetEntity=Interroger::class, mappedBy="categorie")
     */
    private $interrogers;

    /**
     * @ORM\OneToMany(targetEntity=Sondage::class, mappedBy="categorie")
     */
    private $sondages;

    public function __construct()
    {
        $this->interrogers = new ArrayCollection();
        $this->sondages = new ArrayCollection();
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

    
    public function __toString()
    {
        return $this->titre;
    }

   
    
    /**
     * @return Collection<int, Interroger>
     */
    public function getInterrogers(): Collection
    {
        return $this->interrogers;
    }

    public function addInterroger(Interroger $interroger): self
    {
        if (!$this->interrogers->contains($interroger)) {
            $this->interrogers[] = $interroger;
            $interroger->addCategorie($this);
        }

        return $this;
    }

    public function removeInterroger(Interroger $interroger): self
    {
        if ($this->interrogers->removeElement($interroger)) {
            $interroger->removeCategorie($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Sondage>
     */
    public function getSondages(): Collection
    {
        return $this->sondages;
    }

    public function addSondage(Sondage $sondage): self
    {
        if (!$this->sondages->contains($sondage)) {
            $this->sondages[] = $sondage;
            $sondage->setCategorie($this);
        }

        return $this;
    }

    public function removeSondage(Sondage $sondage): self
    {
        if ($this->sondages->removeElement($sondage)) {
            // set the owning side to null (unless already changed)
            if ($sondage->getCategorie() === $this) {
                $sondage->setCategorie(null);
            }
        }

        return $this;
    }

}
