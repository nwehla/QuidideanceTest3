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
     * @ORM\OneToMany(targetEntity=Survey::class, mappedBy="categorie")
     */
    private $surveys;

    /**
     * @ORM\ManyToMany(targetEntity=Interroger::class, mappedBy="categorie")
     */
    private $interrogers;

    public function __construct()
    {
        $this->surveys = new ArrayCollection();
        $this->interrogers = new ArrayCollection();
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
     * @return Collection<int, Survey>
     */
    public function getSurveys(): Collection
    {
        return $this->surveys;
    }

    public function addSurvey(Survey $survey): self
    {
        if (!$this->surveys->contains($survey)) {
            $this->surveys[] = $survey;
            $survey->setCategorie($this);
        }

        return $this;
    }

    public function removeSurvey(Survey $survey): self
    {
        if ($this->surveys->removeElement($survey)) {
            // set the owning side to null (unless already changed)
            if ($survey->getCategorie() === $this) {
                $survey->setCategorie(null);
            }
        }

        return $this;
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

}
