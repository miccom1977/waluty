<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"username"}, message="Taki login już istnieje w systemie")
 * @UniqueEntity(fields={"email"}, message="Taki e-mail już istnieje w systemie")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255 )
     */
    private $sername;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="integer")
     */
    private $mailingActivate;

    /**
     * @ORM\Column(type="integer")
     */

    private $phone;

    /**
     * @ORM\Column(name="birthday", type="integer", nullable=true)
     */
    private $birthday;



    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Return birthday
     *
     * @return \Timestamp
     */

    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @return User
     */


    public function setBirthday( $birthday )
    {
        $this->birthday = $birthday;

        return $this;
    }




    public function getSername(): ?string
    {
        return $this->sername;
    }

    public function setSername(string $sername): self
    {
        $this->sername = $sername;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getMailingActivate(): ?int
    {
        return $this->mailingActivate;
    }

    public function setMailingActivate(int $mailingActivate): self
    {
        $this->mailingActivate = $mailingActivate;

        return $this;
    }

    public function getRoles(): array
    {
        return [
            'ROLE_USER'
        ];
    }

    public function getSalt() {}

    public function eraseCredentials() {}

    public function serialize() {
        return serialize([
            $this->id,
            $this->username,
            $this->id,
            $this->email,
            $this->password
        ]);

    }


    public function unserialize($string) {
        list(
            $this->id,
            $this->username,
            $this->id,
            $this->email,
            $this->password
        )= unserialize($string, ['allowed_classes'=> false] );

    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }
    
}
