<?php

namespace App\Dto\User;

use App\Dto\IDto;
use Symfony\Component\Validator\Constraints as Assert;

class LoginUserDto implements IDto
{
    /**
     * @Assert\Email
     * @Assert\NotBlank
     *
     * @var string|null
     */
    private ?string $email;

    /**
     * @Assert\NotBlank
     *
     * @var string|null
     */
    private ?string $password;

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return LoginUserDto
     */
    public function setEmail(?string $email): LoginUserDto
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     * @return LoginUserDto
     */
    public function setPassword(?string $password): LoginUserDto
    {
        $this->password = $password;
        return $this;
    }
}
