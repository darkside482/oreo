<?php

namespace App\Controller\User;

use App\Dto\User\LoginUserDto;
use App\Dto\User\RegisterUserDto;
use App\Enum\ErrorCode;
use App\Exception\ClientErrorException;
use App\Form\Type\User\LoginUserDtoType;
use App\Form\Type\User\RegisterUserDtoType;
use App\Handler\User\UserHandler;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/user")
 *
 * Class UserController
 * @package App\Controller\User
 */
class UserController extends AbstractController
{
    private UserHandler $handler;

    /**
     * UserController constructor.
     * @param UserHandler $handler
     */
    public function __construct(UserHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     *
     * @Route("/sign-up", name="user_sign_up")
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ClientErrorException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function registerAction(Request $request): JsonResponse
    {
        $dto = new RegisterUserDto();

        $form = $this->createForm(RegisterUserDtoType::class, $dto);
        $form->submit($request->toArray());

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->handler->register($dto);

            $serializer = new Serializer([new ObjectNormalizer()]);

            return new JsonResponse($serializer->normalize($user), Response::HTTP_CREATED);
        }

        throw new ClientErrorException($form->getErrors(true), ErrorCode::INVALID_DATA);
    }

    /**
     *
     * @Route("/login", name="user_login")
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ClientErrorException
     */
    public function loginAction(Request $request): JsonResponse
    {
        $dto = new LoginUserDto();

        $form = $this->createForm(LoginUserDtoType::class, $dto);
        $form->submit($request->toArray());

        if ($form->isSubmitted() && $form->isValid())
        {
            $token = $this->handler->login($dto);

            return new JsonResponse(['X-USER-TOKEN' => $token], Response::HTTP_OK);
        }

        throw new ClientErrorException($form->getErrors(true), ErrorCode::INVALID_DATA);
    }
}
