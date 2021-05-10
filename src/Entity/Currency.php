<?php

namespace App\Entity;

use App\Repository\CurrencyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CurrencyRepository::class)
 */
class Currency
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
    private $currencyName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $currencyCode;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=4)
     */
    private $currencyBid;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=4)
     */
    private $currencyAsk;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrencyName(): ?string
    {
        return $this->currencyName;
    }

    public function setCurrencyName( string $currencyName ): self
    {
        $this->currencyName = $currencyName;

        return $this;
    }

    public function getCurrencyCode(): ?string
    {
        return $this->currencyCode;
    }

    public function setCurrencyCode( string $currencyCode ): self
    {
        $this->currencyCode = $currencyCode;

        return $this;
    }

    public function getCurrencyBid(): ?string
    {
        return $this->currencyBid;
    }

    public function setCurrencyBid( string $currencyBid ): self
    {
        $this->currencyBid = $currencyBid;

        return $this;
    }

    public function getCurrencyAsk(): ?string
    {
        return $this->currencyAsk;
    }

    public function setCurrencyAsk( string $currencyAsk ): self
    {
        $this->currencyAsk = $currencyAsk;

        return $this;
    }

}
