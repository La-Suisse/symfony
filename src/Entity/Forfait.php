<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ForfaitRepository")
 */
class Forfait
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeFrais")
     * @ORM\JoinColumn(nullable=false)
     */
    private $monType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FicheFrais")
     * @ORM\JoinColumn(nullable=false)
     */
    private $maFiche;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantite;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMonType(): ?TypeFrais
    {
        return $this->monType;
    }

    public function setMonType(?TypeFrais $monType): self
    {
        $this->monType = $monType;

        return $this;
    }

    public function getMaFiche(): ?FicheFrais
    {
        return $this->maFiche;
    }

    public function setMaFiche(?FicheFrais $maFiche): self
    {
        $this->maFiche = $maFiche;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }
}
