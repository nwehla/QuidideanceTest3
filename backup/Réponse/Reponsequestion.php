<?php

namespace App\Entity;

use App\Repository\ReponsequestionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReponsequestionRepository::class)
 */
class Reponsequestion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
  
    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $datecreation;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $datemiseajour;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titre;

    /**
     * @ORM\ManyToOne(targetEntity=interroger::class, inversedBy="reponsequestions")
     */
    private $question;

    public function getId(): ?int
    {
        return $this->id;
    }    

    public function getDatecreation(): ?\DateTimeImmutable
    {
        return $this->datecreation;
    }

    public function setDatecreation(?\DateTimeImmutable $datecreation): self
    {
        $this->datecreation = $datecreation;

        return $this;
    }

    public function getDatemiseajour(): ?\DateTimeImmutable
    {
        return $this->datemiseajour;
    }

    public function setDatemiseajour(?\DateTimeImmutable $datemiseajour): self
    {
        $this->datemiseajour = $datemiseajour;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
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

    public function getQuestion(): ?interroger
    {
        return $this->question;
    }

    public function setQuestion(?interroger $question): self
    {
        $this->question = $question;

        return $this;
    }
}
