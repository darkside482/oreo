<?php

namespace App\Handler\User;

use App\Dto\User\LoginUserDto;
use App\Dto\User\RegisterUserDto;
use App\Entity\User\User;
use App\Enum\ErrorCode;
use App\Exception\ClientErrorException;
use App\Manager\TimeManager;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserHandler
{

    private ManagerRegistry $managerRegistry;

    private TimeManager $timeManager;

    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(
        ManagerRegistry $managerRegistry,
        TimeManager $timeManager,
        UserPasswordEncoderInterface $passwordEncoder
    )
    {
        $this->managerRegistry = $managerRegistry;
        $this->timeManager = $timeManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param RegisterUserDto $dto
     * @return User
     * @throws ClientErrorException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function register(RegisterUserDto $dto): User
    {
        /** @var EntityManager $em */
        $em = $this->managerRegistry->getManager();

        $em->beginTransaction();

        try {

            $user = new User();

            $encodedPassword = $this->passwordEncoder->encodePassword($user, $dto->getPassword());
            $user
                ->setEmail($dto->getEmail())
                ->setName($dto->getName())
                ->setPassword($encodedPassword)
                ->setCreatedAt($this->timeManager->getTime());

            $em->persist($user);
            $em->flush();
        } catch (UniqueConstraintViolationException) {
            $em->rollback();
            throw new ClientErrorException('user_with_such_email_already_exists', ErrorCode::SAME_EMAIL);
        } catch (Exception $e) {
            $em->rollback();
            throw $e;
        }

        $em->commit();

        return $user;
    }

    public function login(LoginUserDto $dto): string
    {
        /** @var EntityManager $em */
        $em = $this->managerRegistry->getManager();

        /** @var User $user */
        $user = $em->getRepository(User::class)->findOneBy(['email' => $dto->getEmail()]);

        if ($user === null) {
            throw new ClientErrorException('user_not_exists', ErrorCode::USER_NOT_EXISTS);
        }

        if ($this->passwordEncoder->isPasswordValid($user, $dto->getPassword()) === false) {
            throw new ClientErrorException('invalid_password', ErrorCode::INVALID_PASSWORD);
        }

        return ''; //TODO: should return user info
    }
}