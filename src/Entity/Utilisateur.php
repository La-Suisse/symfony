<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UtilisateurRepository")
 */
class Utilisateur
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $identifiant;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $mdp;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FicheFrais", mappedBy="monUtilisateur")
     * @ORM\OrderBy({"date" = "DESC"})
     */
    private $maFicheFrais;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeUtilisateur")
     * @ORM\JoinColumn(nullable=false)
     */
    private $monType;

    public function __construct()
    {
        $this->maFicheFrais = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getIdentifiant(): ?string
    {
        return $this->identifiant;
    }

    public function setIdentifiant(string $identifiant): self
    {
        $this->identifiant = $identifiant;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): self
    {
        $this->mdp = $mdp;

        return $this;
    }

    /**
     * @return Collection|FicheFrais[]
     */
    public function getMaFicheFrais(): Collection
    {
        return $this->maFicheFrais;
    }

    public function addMaFicheFrai(FicheFrais $maFicheFrai): self
    {
        if (!$this->maFicheFrais->contains($maFicheFrai)) {
            $this->maFicheFrais[] = $maFicheFrai;
            $maFicheFrai->setMonUtilisateur($this);
        }

        return $this;
    }

    public function removeMaFicheFrai(FicheFrais $maFicheFrai): self
    {
        if ($this->maFicheFrais->contains($maFicheFrai)) {
            $this->maFicheFrais->removeElement($maFicheFrai);
            // set the owning side to null (unless already changed)
            if ($maFicheFrai->getMonUtilisateur() === $this) {
                $maFicheFrai->setMonUtilisateur(null);
            }
        }

        return $this;
    }

    public function getMonType(): ?TypeUtilisateur
    {
        return $this->monType;
    }

    public function setMonType(?TypeUtilisateur $monType): self
    {
        $this->monType = $monType;

        return $this;
    }
}
