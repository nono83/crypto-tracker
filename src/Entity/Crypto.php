<?php

namespace App\Entity;

use App\Repository\CryptoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CryptoRepository::class)
 */
class Crypto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $symbol;

    /**
     * @ORM\Column(type="integer")
     */
    private $apiID;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $logo;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\OneToMany(targetEntity=Investment::class, mappedBy="crypto")
     */
    private $investments;

    /**
     * @ORM\Column(type="decimal", precision=7, scale=2)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $evolution;

    public function __construct()
    {
        $this->investments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): self
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getApiID(): ?int
    {
        return $this->apiID;
    }

    public function setApiID(int $apiID): self
    {
        $this->apiID = $apiID;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return Collection<int, Investment>
     */
    public function getInvestments(): Collection
    {
        return $this->investments;
    }

    public function addInvestment(Investment $investment): self
    {
        if (!$this->investments->contains($investment)) {
            $this->investments[] = $investment;
            $investment->setCrypto($this);
        }

        return $this;
    }

    public function removeInvestment(Investment $investment): self
    {
        if ($this->investments->removeElement($investment)) {
            // set the owning side to null (unless already changed)
            if ($investment->getCrypto() === $this) {
                $investment->setCrypto(null);
            }
        }

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }
 
    public function getEvolution(): ?string
    {
        return $this->evolution;
    }

    public function setEvolution(string $evolution): self
    {
        $this->evolution = $evolution;

        return $this;
    }
}
