<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReponseRepository::class)
 */
class Reponse
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $datecreation;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $datemiseajour;

    /**
     * @ORM\ManyToOne(targetEntity=Interroger::class, inversedBy="reponses")
     */
    private $question;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
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

    public function getQuestion(): ?Interroger
    {
        return $this->question;
    }

    public function setQuestion(?Interroger $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function __toString()
    {
        return $this->titre;
    }
}
