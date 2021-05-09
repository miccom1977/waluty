<?php

namespace App\Entity;

use App\Repository\UserCurrencyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserCurrencyRepository::class)
 */
class UserCurrency
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $user_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $currency_id;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=4)
     */
    private $currencyMin;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=4)
     */
    private $currencyMax;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getCurrencyId(): ?int
    {
        return $this->currency_id;
    }

    public function setCurrencyId(int $currency_id): self
    {
        $this->currency_id = $currency_id;

        return $this;
    }

    public function getCurrencyMin(): ?string
    {
        return $this->currencyMin;
    }

    public function setCurrencyMin(string $currencyMin): self
    {
        $this->currencyMin = $currencyMin;

        return $this;
    }

    public function getCurrencyMax(): ?string
    {
        return $this->currencyMax;
    }

    public function setCurrencyMax(string $currencyMax): self
    {
        $this->currencyMax = $currencyMax;

        return $this;
    }
}
