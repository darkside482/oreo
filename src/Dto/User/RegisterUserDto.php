<?php

namespace App\Dto\User;

use App\Dto\IDto;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class RegisterUserDto
 * @package App\Dto\User
 */
class RegisterUserDto implements IDto
{
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
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
     * @Assert\IdenticalTo(propertyPath="password", message="confirm password must be the same as password")
     *
     * @var string|null
     */
    private ?string $confirmPassword;

    /**
     * @Assert\NotBlank
     *
     * @var string|null
     */
    private ?string $name;

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return RegisterUserDto
     */
    public function setEmail(?string $email): RegisterUserDto
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
     * @return RegisterUserDto
     */
    public function setPassword(?string $password): RegisterUserDto
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    /**
     * @param string|null $confirmPassword
     * @return RegisterUserDto
     */
    public function setConfirmPassword(?string $confirmPassword): RegisterUserDto
    {
        $this->confirmPassword = $confirmPassword;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return RegisterUserDto
     */
    public function setName(?string $name): RegisterUserDto
    {
        $this->name = $name;
        return $this;
    }
}
