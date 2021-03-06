<?php

namespace App\Entity;

use App\Repository\SondageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SondageRepository::class)
 */
class Sondage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titre;

    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $multiple;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $statut;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $messagefermeture;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datecreation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datemiseajour;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datedefermeture;

        
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

   
   

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="sondages")
     */
    private $categorie;

    /**
     * @ORM\OneToMany(targetEntity=Repondant::class, mappedBy="sondage")
     */
    private $repondant;

    /**
     * @ORM\OneToMany(targetEntity=Interroger::class, mappedBy="sondage")
     */
    private $questions;

    /**
     * @ORM\OneToMany(targetEntity=Reponse::class, mappedBy="sondage",cascade={"persist"})
     */
    private $reponses;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $question;

    public function __construct()
    {
        $this->repondant = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->reponses = new ArrayCollection();
    }
    

   
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMultiple(): ?bool
    {
        return $this->multiple;
    }

    public function setMultiple(?bool $multiple): self
    {
        $this->multiple = $multiple;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getMessagefermeture(): ?string
    {
        return $this->messagefermeture;
    }

    public function setMessagefermeture(?string $messagefermeture): self
    {
        $this->messagefermeture = $messagefermeture;

        return $this;
    }

    public function getDatecreation(): ?\DateTimeInterface
    {
        return $this->datecreation;
    }

    public function setDatecreation(?\DateTimeInterface $datecreation): self
    {
        $this->datecreation = $datecreation;

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

    public function getDatedefermeture(): ?\DateTimeInterface
    {
        return $this->datedefermeture;
    }

    public function setDatedefermeture(?\DateTimeInterface $datedefermeture): self
    {
        $this->datedefermeture = $datedefermeture;

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

    
    // 
    
    public function getCategorie(): ?Categories
    {
        return $this->categorie;
    }

    public function setCategorie(?Categories $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, Repondant>
     */
    public function getRepondant(): Collection
    {
        return $this->repondant;
    }

    public function addRepondant(Repondant $repondant): self
    {
        if (!$this->repondant->contains($repondant)) {
            $this->repondant[] = $repondant;
            $repondant->setSondage($this);
        }

        return $this;
    }

    public function removeRepondant(Repondant $repondant): self
    {
        if ($this->repondant->removeElement($repondant)) {
            // set the owning side to null (unless already changed)
            if ($repondant->getSondage() === $this) {
                $repondant->setSondage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Interroger>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Interroger $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setSondage($this);
        }

        return $this;
    }

    public function removeQuestion(Interroger $question): self
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getSondage() === $this) {
                $question->setSondage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reponse>
     */
    public function getReponses(): Collection
    {
        return $this->reponses;
    }

    public function addReponse(Reponse $reponse): self
    {
        if (!$this->reponses->contains($reponse)) {
            $this->reponses[] = $reponse;
            $reponse->setSondage($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): self
    {
        if ($this->reponses->removeElement($reponse)) {
            // set the owning side to null (unless already changed)
            if ($reponse->getSondage() === $this) {
                $reponse->setSondage(null);
            }
        }

        return $this;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }    
}
